<?php
require_once "config/Database.php";
require_once "models/Student.php";
require_once "contracts/IBaseRepository.php";

class StudentRepository implements IBaseRepository
{
    protected $databaseContext;
    protected $table;

    public function __construct($databaseContext, $table = "students")
    {
        $this->databaseContext = $databaseContext;
        $this->table = $table;
    }

    public function GetAllList(): array
    {
        $query = "SELECT STUD_ID, STUD_NAME, STUD_M_SCORE, STUD_F_SCORE FROM {$this->table}";
        $result = $this->ExecuteSqlQuery($query, []);
        return $this->BuildResultList($result);
    }

    public function GetFinalGrade(): array
    {
        $query = "SELECT * FROM {$this->table}";
        $result = $this->ExecuteSqlQuery($query, []);
        return $this->BuildResultList($result);
    }

    public function GetById(int $id): ?Student
    {
        $query = "SELECT * FROM {$this->table} WHERE STUD_ID = :id";
        $result = $this->ExecuteSqlQuery($query, [':id' => $id]);
        return $this->BuildResult($result);
    }

    public function Add($entity)
    {
        $student = new Student();
        $student->id = $entity['id'];
        $student->name = $entity['name'];
        $student->midtermScore = $entity['midtermScore'];
        $student->finalScore = $entity['finalScore'];
        $student->finalGrade = $entity['finalGrade'];
        $student->status = $entity['status'];

        $query = "INSERT INTO {$this->table} 
                    (STUD_ID, 
                    STUD_NAME, 
                    STUD_M_SCORE, 
                    STUD_F_SCORE, 
                    STUD_F_GRADE, 
                    STUD_STATUS) 
                VALUES (:id ,:name, :midterm, :final, :grade, :status)";

        $params = [
            ':id'      => $student->id,
            ':name'    => $student->name,
            ':midterm' => $student->midtermScore,
            ':final'   => $student->finalScore,
            ':grade'   => $student->finalGrade,
            ':status'  => $student->status,
        ];

        $this->ExecuteSqlQuery($query, $params);
        return $student;
    }


    public function Update($entity): void
    {
        $query = "UPDATE {$this->table} 
              SET STUD_NAME = :name, 
                  STUD_M_SCORE = :midterm, 
                  STUD_F_SCORE = :final, 
                  STUD_F_GRADE = :grade, 
                  STUD_STATUS = :status 
              WHERE STUD_ID = :id";

        $params = [
            ':id'     => $entity['id'],
            ':name'   => $entity['name'],
            ':midterm' => $entity['midtermScore'],
            ':final'  => $entity['finalScore'],
            ':grade'  => $entity['finalGrade'],
            ':status' => $entity['status']
        ];
        $this->ExecuteSqlQuery($query, $params);
    }

    public function Delete(int $id): void
    {
        $query = "DELETE FROM {$this->table} WHERE STUD_ID = :id";
        $params = [':id' => $id];
        $this->ExecuteSqlQuery($query, $params);
    }

    private function ExecuteSqlQuery(string $query, array $params)
    {
        $statementObject = $this->databaseContext->prepare($query);
        $statementObject->execute($params);

        if (stripos($query, "SELECT") === 0) {
            return $statementObject->fetchAll(PDO::FETCH_ASSOC);
        }

        return null;
    }

    private function BuildResult(?array $result): ?Student
    {
        if (!$result || empty($result[0])) {
            return null;
        }

        $row = $result[0];

        $student = new Student();
        $student->id = $row['STUD_ID'];
        $student->name = $row['STUD_NAME'];
        $student->midtermScore = $row['STUD_M_SCORE'];
        $student->finalScore = $row['STUD_F_SCORE'];
        $student->finalGrade = $row['STUD_F_GRADE'];
        $student->status = $row['STUD_STATUS'];

        return $student;
    }

    private function BuildResultList(array $result): array
    {
        $students = [];

        foreach ($result as $row) {
            $student = new Student();
            $student->id = $row['STUD_ID'];
            $student->name = $row['STUD_NAME'];
            $student->midtermScore = $row['STUD_M_SCORE'];
            $student->finalScore = $row['STUD_F_SCORE'];

            $students[] = $student;
        }

        return $students;
    }
}
