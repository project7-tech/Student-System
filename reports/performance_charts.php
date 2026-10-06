<?php
require_once '../config/auth.php';
require_login('../login.php');
include '../config/db.php';

$where = student_scope_sql();
$query = "SELECT students.id AS student_id,
                 students.reg_no,
                 students.fullname,
                 courses.course_code,
                 courses.course_name,
                 results.marks,
                 results.grade
          FROM results
          JOIN students ON results.student_id = students.id
          JOIN courses ON results.course_id = courses.id
          $where
          ORDER BY students.fullname, courses.course_code";
$result = mysqli_query($conn, $query);

$students = [];
$courses = [];
$scoreRows = [];
$palette = ['#2f5597', '#c0504d', '#9bbb59', '#8064a2', '#4bacc6', '#f79646', '#7f7f7f', '#00a65a'];

while($row = mysqli_fetch_assoc($result)){
    $studentId = (int)$row['student_id'];
    $courseCode = $row['course_code'];

    if(!isset($students[$studentId])){
        $students[$studentId] = [
            'name' => $row['fullname'],
            'reg_no' => $row['reg_no'],
            'scores' => [],
        ];
    }

    if(!isset($courses[$courseCode])){
        $courses[$courseCode] = [
            'name' => $row['course_name'],
            'color' => $palette[count($courses) % count($palette)],
        ];
    }

    $students[$studentId]['scores'][$courseCode] = (int)$row['marks'];
    $scoreRows[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Performance Charts</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="page">
<a class="back-link" href="../dashboard.php">&larr; Dashboard</a>
<section class="panel">
<h2>Performance Charts</h2>

<?php if(count($students) === 0){ ?>
    <p class="muted">No marks have been uploaded yet.</p>
<?php } else { ?>
<div class="performance-layout">
    <div class="bar-chart-wrap">
        <div class="bar-chart student-score-chart">
            <div class="chart-scale">
                <span>100</span>
                <span>80</span>
                <span>60</span>
                <span>40</span>
                <span>20</span>
                <span>0</span>
            </div>
            <div class="chart-plot">
                <?php foreach($students as $student){ ?>
                <div class="bar-group" title="<?php echo e($student['name']); ?>">
                    <div class="bar-stack">
                        <?php foreach($courses as $courseCode => $course){
                            $mark = $student['scores'][$courseCode] ?? 0;
                        ?>
                        <span class="vbar score-bar" style="height: <?php echo e($mark); ?>%; background: <?php echo e($course['color']); ?>">
                            <b><?php echo $mark > 0 ? e($mark) : ''; ?></b>
                        </span>
                        <?php } ?>
                    </div>
                    <div class="bar-label"><?php echo e($student['reg_no']); ?></div>
                </div>
                <?php } ?>
            </div>
            <div class="chart-legend">
                <?php foreach($courses as $courseCode => $course){ ?>
                <span><i class="legend-dot" style="background: <?php echo e($course['color']); ?>"></i><?php echo e($courseCode); ?></span>
                <?php } ?>
            </div>
        </div>
    </div>

    <aside class="score-sidebar">
        <h3>Student Scores</h3>
        <table class="score-table">
            <tr>
                <th>Student</th>
                <th>Course</th>
                <th>Marks</th>
                <th>Grade</th>
            </tr>
            <?php foreach($scoreRows as $row){ ?>
            <tr>
                <td><?php echo e($row['fullname']); ?></td>
                <td><?php echo e($row['course_code']); ?></td>
                <td><?php echo e($row['marks']); ?></td>
                <td><?php echo e($row['grade']); ?></td>
            </tr>
            <?php } ?>
        </table>
    </aside>
</div>
<?php } ?>
</section>
</main>
</body>
</html>
