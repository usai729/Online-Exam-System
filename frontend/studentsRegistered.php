<!DOCTYPE html>
<html>
<head>
    <title>Students Details</title>

    <!-- External CSS -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- External JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <style>
        @import url("https://fonts.googleapis.com/css?family=Roboto");

        body {
            font-family: 'Roboto';
        }

        table th, td {
            text-align: center;
        }
    </style>
</head>

<body>
    <div style="text-align: center;">
        <h1>Panchavati</h1>
    </div>

    <hr>
    <br>
    
    <div class="container">
        <table id="studentsTable" class="dataTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Exams Attended</th>
                    <th>Avg Score/Total Score</th>
                    <th class="no-sort">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $conn = mysqli_connect("localhost", "root", "rootmysql@1#", "panchavati");

                    $sql_students = "SELECT * FROM student ORDER BY stu_name DESC";
                    $result_students = mysqli_query($conn, $sql_students);

                    while ($row_student = mysqli_fetch_assoc($result_students)) {
                        $sql_results = "SELECT scheduledExams.examSubject_name, scheduledExams.examDate, SUM(results.score) AS score, AVG(results.score) AS average, results.max_marks, COUNT(*) AS count_tot FROM results INNER JOIN scheduledExams ON scheduledExams.EpID = results.exam WHERE results.student='" . $row_student['SpID'] . "'";
                        $result_results = mysqli_query($conn, $sql_results);
                        $result = mysqli_fetch_assoc($result_results);

                        $SpID = $row_student['SpID'];
                        $blocked = $row_student['blocked'] == 1 ? true : false;

                        $scoreSet = $result['score'] != 0 || $result['score'] != null ?  $result['average'] . "/" . $result['score'] : "N/A";

                        echo "<tr>";
                            echo "<td>" . $row_student['stu_name'] . "</td>";
                            echo "<td>" . $row_student['stu_id'] . "</td>";
                            echo "<td>" . $result['count_tot'] . "</td>";
                            echo "<td>" . $scoreSet . "</td>";
                            echo "<td>";
                                echo $blocked ? '<button class="deleteButton" data-student-id="' . $row_student['SpID'] . '" id="'.$SpID.'" style="cursor: pointer;" onclick="block_unblock(this)">Unblock</button>' : '<button class="deleteButton" data-student-id="' . $row_student['SpID'] . '" id="'.$SpID.'" style="cursor: pointer;" onclick="block_unblock(this)">Block</button>';
                            echo "</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
        <a href="./admin.html">Admin Home</a>
    </div>

    <script>
        $(document).ready(function() {
            $('#studentsTable').DataTable();
        });

        function block_unblock(stu) {
            var stu_id = stu.id;
            var formdata = new FormData();

            formdata.append("stu", stu_id);

            fetch("../backend/blockSetting.php", {
                method: "POST",
                body: formdata
            }).then(r1 => {
                return r1.text();
            }).then(r2 => {
                if (r2 == "unblocked") {
                    stu.innerHTML = "Block";
                } else {
                    stu.innerHTML = "Unblock";
                }
            })
        }
    </script>
</body>
</html>
