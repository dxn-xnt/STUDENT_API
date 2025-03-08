<?php
require_once "service/StudentService.php";
require_once "repositories/StudentRepository.php";
require_once "config/Database.php";

class StudentController
{
    private StudentService $studentService;

    public function __construct()
    {
        $database = Database::getInstance();
        $dbConnection = $database->getConnection();
        $this->studentService = new StudentService($dbConnection);
    }

    public function getAllStudents(): void
    {
        $students = $this->studentService->getAllGrades();
        echo json_encode($students);
    }

    public function getStudentById($id): void
    {
        $student = $this->studentService->getStudent($id);
        echo json_encode($student);
    }

    public function addStudent($student)
    {
        $result = $this->studentService->addStudent($student);
        echo json_encode($result);
    }

    public function updateStudent($id)
    {
        $studentData = json_decode(file_get_contents("php://input"), true);
        $result = $this->studentService->updateStudent($id, $studentData);
        echo json_encode($result);
    }

    public function deleteStudent($id): void
    {
        $result = $this->studentService->deleteStudent($id);
        echo json_encode($result);
    }

    public function getAllGrades(): void
    {
        $students = $this->studentService->getAllGrades();
        echo json_encode($students);
    }
}
