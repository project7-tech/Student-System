<?php
require_once '../config/auth.php';
require_login('../login.php');
include '../config/db.php';

$query = "SELECT students.id AS student_id,
                 students.fullname,
                 students.department,
                 courses.course_name,
                 courses.course_code,
                 results.marks,
                 results.gpa
          FROM students
          JOIN results ON students.id = results.student_id
          JOIN courses ON results.course_id = courses.id
          " . student_scope_sql() . "
          ORDER BY students.fullname, results.marks DESC";

$result = mysqli_query($conn, $query);

$students = [];
while($row = mysqli_fetch_assoc($result)){
    $studentId = (int)$row['student_id'];
    if(!isset($students[$studentId])){
        $students[$studentId] = [
            'fullname' => $row['fullname'],
            'department' => $row['department'],
            'courses' => [],
            'total_marks' => 0,
            'total_gpa' => 0,
            'count' => 0,
        ];
    }

    $students[$studentId]['courses'][] = [
        'course_name' => $row['course_name'],
        'course_code' => $row['course_code'],
        'marks' => (int)$row['marks'],
        'gpa' => (float)$row['gpa'],
    ];
    $students[$studentId]['total_marks'] += (int)$row['marks'];
    $students[$studentId]['total_gpa'] += (float)$row['gpa'];
    $students[$studentId]['count']++;
}

function professional_recommendation($averageMarks, $averageGpa) {
    if($averageMarks >= 80 || $averageGpa >= 3.7){
        return [
            'area' => 'Competence and Detail',
            'statement' => 'Her keen attention to detail, commitment to academic excellence, and ability to analyze students work critically make her well-suited for the role of an examiner.'
        ];
    }

    if($averageMarks >= 70 || $averageGpa >= 3.0){
        return [
            'area' => 'Technical Proficiency',
            'statement' => 'His familiarity with national examination marking schemes and assessment rubrics ensures accuracy and fairness in evaluating candidates.'
        ];
    }

    if($averageMarks >= 60 || $averageGpa >= 2.5){
        return [
            'area' => 'Professional Integrity',
            'statement' => 'She is a professional of high integrity and reliability, qualities that are essential for the execution of examination-related duties.'
        ];
    }

    return [
        'area' => 'Efficiency under Pressure',
        'statement' => 'Her ability to work efficiently under strict timelines, coupled with her thorough understanding of the subject syllabus, makes her a strong candidate.'
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recommendations</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="page">
<a class="back-link" href="../dashboard.php">&larr; Dashboard</a>
<section class="panel">
<h2>Professional Recommendations</h2>

<table>
<tr>
    <th>Student</th>
    <th>Average Marks</th>
    <th>Average GPA</th>
    <th>Status</th>
    <th>Recommendation Focus</th>
    <th>Recommendation</th>
</tr>

<?php foreach($students as $student){
    $averageMarks = $student['count'] ? $student['total_marks'] / $student['count'] : 0;
    $averageGpa = $student['count'] ? $student['total_gpa'] / $student['count'] : 0;
    $status = $averageMarks >= 75 ? 'Excellent' : ($averageMarks >= 60 ? 'Good' : ($averageMarks >= 50 ? 'Fair' : 'Needs Support'));
    $recommendation = professional_recommendation($averageMarks, $averageGpa);
?>
<tr>
    <td><?php echo e($student['fullname']); ?></td>
    <td><?php echo e(number_format($averageMarks, 2)); ?></td>
    <td><?php echo e(number_format($averageGpa, 2)); ?></td>
    <td><span class="badge"><?php echo e($status); ?></span></td>
    <td><?php echo e($recommendation['area']); ?></td>
    <td><?php echo e($recommendation['statement']); ?></td>
</tr>

<?php } ?>
</table>

<?php if(count($students) === 0){ ?>
    <p class="muted">No marks have been uploaded yet, so recommendations cannot be generated.</p>
<?php } ?>
</section>
</main>
</body>
</html>
