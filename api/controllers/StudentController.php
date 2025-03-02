<?php
require_once "service/StudentService.php";
require_once "repositories/StudentRepository.php";
require_once "config/Database.php";

class StudentController
{
    private StudentRepository $studentRepository;
    private StudentService $studentService;

    public function __construct()
    {
        $database = Database::getInstance();
        $dbConnection = $database->getConnection();
        $this->studentRepository = new StudentRepository($dbConnection, "students");
        $this->studentService = new StudentService($dbConnection);
    }

    public function getAllStudents(): void
    {
        $students = $this->studentRepository->GetAllList();
        echo json_encode($students);
    }

    public function getStudentById(int $id): void
    {
        $student = $this->studentRepository->GetById($id);
        echo json_encode($student);
    }

    public function addStudent($student)
    {
        $this->studentService->addStudent($student);
        echo "Student Added Successfully";
    }

    public function updateStudent($id)
    {
        $studentData = json_decode(file_get_contents("php://input"), true);
        $this->studentService->updateStudent($id, $studentData);
        echo "Student Updated Successfully";
    }

    public function deleteStudent(int $id): void
    {
        $this->studentRepository->Delete($id);
        echo "Student Deleted Succesfully";
    }

    public function getAllGrades(): void
    {
        $students = $this->studentService->getAllGrades();
        echo json_encode($students);
    }
}
