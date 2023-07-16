<?php

class LabExam {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        echo "\nNew LabExam object created.\n";
    }

    public function insert($patientID, $doctorID, $visitID, $date, $examItem, $result, $normalRange) {
        $sql = "INSERT INTO LabExams (PatientID, DoctorID, VisitID, Date, ExamItem, Result, NormalRange) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiiisss", $patientID, $doctorID, $visitID, $date, $examItem, $result, $normalRange);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function update($labExamID, $result, $normalRange) {
        $sql = "UPDATE LabExams SET Result=?, NormalRange=? WHERE LabExamID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssi", $result, $normalRange, $labExamID);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function delete($labExamID) {
        $sql = "DELETE FROM LabExams WHERE LabExamID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $labExamID);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function selectAll() {
        $sql = "SELECT * FROM LabExams";
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            $labExams = array();
            while ($row = $result->fetch_assoc()) {
                $labExams[] = $row;
            }
            return $labExams;
        } else {
            return false;
        }
    }

    public function selectOne($labExamID) {
        $sql = "SELECT * FROM LabExams WHERE LabExamID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $labExamID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function selectByPatient($patientID) {
        $sql = "SELECT * FROM LabExams WHERE PatientID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $patientID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $labExams = array();
            while ($row = $result->fetch_assoc()) {
                $labExams[] = $row;
            }
            return $labExams;
        } else {
            return false;
        }
    }

    public function selectByDoctor($doctorID) {
    $sql = "SELECT LabExams.*, patients.firstName, patients.lastName, patients.healthCardNumber FROM LabExams INNER JOIN patients ON LabExams.PatientID=patients.patientID WHERE DoctorID=?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $doctorID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $exams = array();
        while ($row = $result->fetch_assoc()) {
            $exams[] = $row;
        }
        return $exams;
    } else {
        return false;
    }
    $stmt->close();
}


   }
   ?>
