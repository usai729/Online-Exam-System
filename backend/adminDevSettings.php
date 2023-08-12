<?php 
    include "./connect.php";

    session_start();

    $requestFor = $_POST['requestFor'];

    function info($session, $conn) {
        $sql_script1 = "SELECT * FROM admin WHERE admin_id='$session'";
        $sql_result = mysqli_fetch_assoc(mysqli_query($conn, $sql_script1));

        $adminTypeDev = $sql_result['devAdmin'] ? true : false;
        $adminAggrement = $sql_result['devAdminAgreed'] ? true : false;
        $adminID = $sql_result['admin_id'];

        echo json_encode(array('type' => $adminTypeDev == 1 ? true : false, "agreement" => $adminAggrement == 1 ? true : false, 'admin' => $adminID));
    }

    function adminAgreement($session, $conn) {
        $sql_script1 = "SELECT * FROM admin WHERE admin_id='$session'";
        $sql_result = mysqli_fetch_assoc(mysqli_query($conn, $sql_script1));

        $adminAggrement = $sql_result['devAdminAgreed'] ? true : false;

        return $adminAggrement;
    }

    function settingUpdate($session, $conn) {
        if (!adminAgreement($session, $conn)) {
            $sql_script1 = "UPDATE admin SET devAdminAgreed=1 WHERE admin_id='$session'";
        } else {
            $sql_script1 = "UPDATE admin SET devAdminAgreed=0 WHERE admin_id='$session'";
        }

        if (mysqli_query($conn, $sql_script1)) {
            echo json_encode(array('status' => true, 'error' => "none"));
        } else {
            echo json_encode(array('status' => false, 'error' => mysqli_error($conn)));
        }
    }

    function registrationSettingAdmin($session, $conn) {
        $sql_script1 = "SELECT * FROM admin WHERE managingAdmin=1";
        $sql_result = mysqli_fetch_assoc(mysqli_query($conn, $sql_script1));

        $managingAdmin = $sql_result['managingAdmin'];
        $regPermit = $sql_result['regPermit'];

        echo json_encode(array("managingAdmin" => $managingAdmin == 1 ? true : false, "regPermit" => $regPermit == 1 ? true : false));
    }

    function currentRegSetting($session, $conn) {
        $sql_script1 = "SELECT * FROM admin WHERE admin_id='$session'";
        $sql_result = mysqli_fetch_assoc(mysqli_query($conn, $sql_script1));

        $adminAggrement = $sql_result['regPermit'] ? true : false;

        return $adminAggrement;
    }

    function regSettingUpdate($session, $conn) {
        if (!currentRegSetting($session, $conn)) {
            $sql_script1 = "UPDATE admin SET regPermit=1 WHERE admin_id='$session'";
        } else {
            $sql_script1 = "UPDATE admin SET regPermit=0 WHERE admin_id='$session'";
        }

        if (mysqli_query($conn, $sql_script1)) {
            echo json_encode(array('status' => true, 'error' => "none"));
        } else {
            echo json_encode(array('status' => false, 'error' => mysqli_error($conn)));
        }
    }

    switch ($requestFor) {
        case 'info':
            info($session = "7013328951ADMIN", $conn);
            break;

        case 'changeAgreement': 
            settingUpdate($session = "7013328951ADMIN", $conn);
            break;

        case 'registrationSettingAdmin':
            registrationSettingAdmin($session = "7013328951ADMIN", $conn);
            break;

        case 'regSettingUpdate':
            regSettingUpdate($session = "7013328951ADMIN", $conn);
            break;
    }
?>