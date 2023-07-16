<?php
class Doctor {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        echo "\nNew Doctor object created.\n";
    }

    public function insert($firstName, $lastName, $email, $phone, $specialization, $role) {
        $sql = "INSERT INTO doctors (FirstName, LastName, Email, Phone, Specialization, Role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssss", $firstName, $lastName, $email, $phone, $specialization, $role);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function update($doctorID, $firstName, $lastName, $email, $phone, $specialization) {
        $sql = "UPDATE doctors SET FirstName=?, LastName=?, Email=?, Phone=?, Specialization=? WHERE DoctorID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssssi", $firstName, $lastName, $email, $phone, $specialization, $doctorID);

        $sql2 = "UPDATE doctor_login SET email=? WHERE doctorID=?";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param("si", $email, $doctorID);

        if ($stmt->execute() && $stmt2->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
        $stmt2->close();
     }
    //     if ($stmt->execute()) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    //     $stmt->close();
    // }

    public function delete($doctorID) {
        $sql = "DELETE FROM doctors WHERE DoctorID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $doctorID);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function selectAll() {
        $sql = "SELECT * FROM doctors";
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            $doctors = array();
            while ($row = $result->fetch_assoc()) {
                $doctors[] = $row;
            }
            return $doctors;
        } else {
            return false;
        }
    }

    public function selectOne($doctorID) {
        $sql = "SELECT * FROM doctors WHERE DoctorID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $doctorID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }
}

 ?>
