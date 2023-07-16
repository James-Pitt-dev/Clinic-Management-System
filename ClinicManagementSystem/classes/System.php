<?php
// System class to handle lab exam entries and update results/normal ranges
// Possibly email sending

//constructor
class System {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // select all from lab exam where Result === 'Pending'
    public function selectPendingLabExams() {
        $sql = "SELECT * FROM LabExams WHERE Result='Pending'";
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

    // Staff updates results and system checks if results are within normal range, then updates normal range
    public function updateLabExamResult($labExamID, $result) {
        $sql = "UPDATE LabExams SET Result=? WHERE LabExamID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $result, $labExamID);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

//    // Compare Lab exam results to normal range and update normal range if necessary
//    // Results can only be 'Abnormal' or 'Normal'
//    public function updateLabExamNormalRange($labExamID, $result) {
//        $sql = "SELECT NormalRange FROM LabExams WHERE LabExamID=?";
//        $stmt = $this->db->prepare($sql);
//        $stmt->bind_param("i", $labExamID);
//        $stmt->execute();
//        $stmt->bind_result($normalRange);
//        $stmt->fetch();
//        $stmt->close();
//        if ($result === 'Abnormal') {
//            $sql = "UPDATE LabExams SET NormalRange=? WHERE LabExamID=?";
//            $stmt = $this->db->prepare($sql);
//            $stmt->bind_param("si", $result, $labExamID);
//            $stmt->execute();
//            if ($stmt->affected_rows > 0) {
//                return true;
//            } else {
//                return false;
//            }
//            $stmt->close();
//        } else if ($result === 'Normal') {
//            if ($normalRange === 'Abnormal') {
//                $sql = "UPDATE LabExams SET NormalRange=? WHERE LabExamID=?";
//                $stmt = $this->db->prepare($sql);
//                $stmt->bind_param("si", $result, $labExamID);
//                $stmt->execute();
//                if ($stmt->affected_rows > 0) {
//                    return true;
//                } else {
//                    return false;
//                }
//                $stmt->close();
//            }
//        }
//    }
    // Compare Lab exam results to normal range and update normal range if necessary
    // Results can only be 'Abnormal', 'Normal', or 'Pending'
    public function updateLabExamNormalRange($labExamID, $result) {
        if ($result === 'Pending') {
            $normalRange = 0;
        } else {
            $normalRange = $result === 'Abnormal' ? 0 : 1;
        }
        $sql = "UPDATE LabExams SET NormalRange=? WHERE LabExamID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $normalRange, $labExamID);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    // Display update lab exam results.
    // Lab exam results should include date, patient name, patient health care number, exam
    //item, result, and normal range.
    public function displayLabExamResults() {
        $sql = "SELECT LabExams.LabExamID, LabExams.Date, Patients.FirstName, Patients.LastName, Patients.HealthCardNumber, LabExams.ExamItem, LabExams.Result, LabExams.NormalRange 
        FROM LabExams 
        INNER JOIN Patients ON LabExams.PatientID = Patients.PatientID
        WHERE LabExams.Result != 'Pending'";
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
}
