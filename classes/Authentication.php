<?php

class Authentication {
    private $db;

    public function __construct($db) {
        $this->db = $db;

    }

    public function createLogin($userID, $userType, $email, $password) {
           switch ($userType) {
               case 'patient':
                   $tableName = 'PATIENT_LOGIN';
                   $idColumnName = 'PatientID';
                   break;
               case 'doctor':
                   $tableName = 'DOCTOR_LOGIN';
                   $idColumnName = 'DoctorID';
                   break;
               case 'staff':
                   $tableName = 'STAFF_LOGIN';
                   $idColumnName = 'StaffID';
                   break;
               default:
                   return false;
           }

           $sql = "INSERT INTO $tableName ($idColumnName, Email, Password) VALUES (?, ?, ?)";
           $stmt = $this->db->prepare($sql);
           $stmt->bind_param("iss", $userID, $email, $password);
           if ($stmt->execute()) {
               return true;
           } else {
               return false;
           }
           $stmt->close();
       }


    public function validateLogin($email, $password, $userType) {
        echo "User Type: $userType<br>";
        // var_dump($userType);

        switch ($userType) {
            case 'patient':
                $tableName = 'PATIENT_LOGIN';
                $idColumnName = 'PatientID';
                $userColumnName = 'Email';
                break;
            case 'doctor':
                $tableName = 'DOCTOR_LOGIN';
                $idColumnName = 'DoctorID';
                $userColumnName = 'Email';
                break;
            case 'staff':
                $tableName = 'STAFF_LOGIN';
                $idColumnName = 'StaffID';
                $userColumnName = 'Email';
                break;
            default:
                return false;
        }

        $sql = "SELECT * FROM $tableName WHERE $userColumnName = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['Password'])) {
                return $row[$idColumnName];
            }
        }

        return false;
    }

}
