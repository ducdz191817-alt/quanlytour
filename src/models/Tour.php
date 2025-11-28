<?php
class Tour
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM tours ORDER BY id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM tours WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO tours (name, type, price, schedule, policy, supplier, image)
                VALUES (:name, :type, :price, :schedule, :policy, :supplier, :image)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($data)
    {
        $sql = "UPDATE tours SET 
                name = :name,
                type = :type,
                price = :price,
                schedule = :schedule,
                policy = :policy,
                supplier = :supplier,
                image = :image
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM tours WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
