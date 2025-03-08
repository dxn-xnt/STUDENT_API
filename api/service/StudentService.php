<?php
require_once "repositories/StudentRepository.php";

class StudentService
{
    private StudentRepository $studentRepository;

    public function __construct($databaseConnection)
    {
        $this->studentRepository = new StudentRepository($databaseConnection);
    }

    public function calculateFinalGrade($midterm, $final): float
    {
        return (0.4 * $midterm) + (0.6 * $final);
    }

    public function determineStatus($finalGrade): string
    {
        return $finalGrade >= 75 ? "Passed" : "Failed";
    }

    public function addStudent($studentData)
    {
        if (!isset($studentData['id'], $studentData['name'], $studentData['midtermScore'], $studentData['finalScore'])) {
            throw new Exception("Invalid input: ID, Name, Midterm Score, and Final Score are required.");
        }
        $studentData['finalGrade'] = $this->calculateFinalGrade($studentData['midtermScore'], $studentData['finalScore']);
        $studentData['status'] = $this->determineStatus($studentData['finalGrade']);

        $student = $this->studentRepository->Add($studentData);
        if (!$student) {
            return ["message" => "Student ID duplicate found."];
        }
        return ["message" => "Student added successfully.", "student" => $student];
    }

    public function getAllGrades()
    {
        $students = $this->studentRepository->GetAllList();
        foreach ($students as $student) {
            $student->finalGrade = $this->calculateFinalGrade($student->midtermScore, $student->finalScore);
            $student->status = $this->determineStatus($student->finalGrade);
        }
        return $students;
    }

    public function getStudent($id)
    {
        $student = $this->studentRepository->GetById($id);
        if (!$student) {
            return ["message" => "Student not found."];
        }

        $student->finalGrade = $this->calculateFinalGrade($student->midtermScore, $student->finalScore);
        $student->status = $this->determineStatus($student->finalGrade);
        return $student;
    }

    public function updateStudent($id, $studentData)
    {
        if (!isset($studentData['id'], $studentData['name'], $studentData['midtermScore'], $studentData['finalScore'])) {
            throw new Exception("Invalid input: Name, Midterm Score, and Final Score are required.");
        }

        $studentData['id'] = $id;
        $studentData['finalGrade'] = $this->calculateFinalGrade($studentData['midtermScore'], $studentData['finalScore']);
        $studentData['status'] = $this->determineStatus($studentData['finalGrade']);
        $isUpdated = $this->studentRepository->Update($id, $studentData);

        if (!$isUpdated) {
            return ["message" => "Student not found."];
        }
        return ["message" => "Student updated successfully.", "student" => $studentData];
    }

    public function deleteStudent($id)
    {
        $isDeleted = $this->studentRepository->Delete($id);

        if (!$isDeleted) {
            return ["message" => "Student not found."];
        }
        return ["message" => "Student deleted successfully.", "student" => $id];
    }
}
