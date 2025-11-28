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
        $sql = "SELECT * FROM tb_tour ORDER BY id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM tb_tour WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO tb_tour 
                (ma_tour, ten_tour, id_danhmuc, gia_tien, ngay_batdau, ngay_ketthuc, mo_ta, lich_trinh, hinh_anh)
                VALUES 
                (:ma_tour, :ten_tour, :id_danhmuc, :gia_tien, :ngay_batdau, :ngay_ketthuc, :mo_ta, :lich_trinh, :hinh_anh)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($data)
    {
        $sql = "UPDATE tb_tour SET 
                ma_tour = :ma_tour,
                ten_tour = :ten_tour,
                id_danhmuc = :id_danhmuc,
                gia_tien = :gia_tien,
                ngay_batdau = :ngay_batdau,
                ngay_ketthuc
                mo_ta = :mo_ta,
                lich_trinh = :lich_trinh,
                hinh_anh = :hinh_anh
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM tb_tour WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
