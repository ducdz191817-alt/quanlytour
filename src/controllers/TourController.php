<?php
require_once './src/models/Tour.php';

class TourController
{
    private $tour;
    private $uploadDir = '/public/dist/assets/uploads/';

    public function __construct($db)
    {
        $this->tour = new Tour($db);
    }

    private function handleUploadImage($file)
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // đảm bảo thư mục tồn tại (server path)
        $serverUploadDir = __DIR__ . '/../../public/dist/assets/uploads/';
        if (!is_dir($serverUploadDir)) {
            mkdir($serverUploadDir, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $target = $serverUploadDir . $newName;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            // trả về đường dẫn public relative để lưu DB (để dùng trong <img src="">)
            return 'public/dist/assets/uploads/' . $newName;
        }

        return null;
    }

    public function index()
    {
        $tours = $this->tour->getAll();
        require './src/views/tour/list.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imgPath = $this->handleUploadImage($_FILES['image'] ?? null);

            $data = [
                'name' => $_POST['name'] ?? '',
                'type' => $_POST['type'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'schedule' => $_POST['schedule'] ?? '',
                'policy' => $_POST['policy'] ?? '',
                'supplier' => $_POST['supplier'] ?? '',
                'image' => $imgPath ?? ($_POST['image'] ?? '')
            ];

            $this->tour->create($data);
            header("Location: index.php?controller=tour&action=index");
            exit;
        }

        require './src/views/tour/create.php';
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=tour&action=index");
            exit;
        }

        $tour = $this->tour->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imgPath = $this->handleUploadImage($_FILES['image'] ?? null);
            $imageToSave = $imgPath ?: ($tour['image'] ?? '');

            $data = [
                'id' => $id,
                'name' => $_POST['name'] ?? '',
                'type' => $_POST['type'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'schedule' => $_POST['schedule'] ?? '',
                'policy' => $_POST['policy'] ?? '',
                'supplier' => $_POST['supplier'] ?? '',
                'image' => $imageToSave
            ];

            $this->tour->update($data);
            header("Location: index.php?controller=tour&action=index");
            exit;
        }

        require './src/views/tour/edit.php';
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            // (opt) bạn có thể xóa file ảnh ở đây nếu muốn
            $this->tour->delete($id);
        }
        header("Location: index.php?controller=tour&action=index");
        exit;
    }

    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=tour&action=index");
            exit;
        }
        $tour = $this->tour->getById($id);
        require './src/views/tour/detail.php';
    }
}
