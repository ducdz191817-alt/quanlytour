<?php include './src/views/layouts/home.php'; ?>

<h2>Danh sách Tour</h2>
<a href="index.php?controller=tour&action=create">+ Thêm tour</a>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Tên tour</th>
        <th>Loại</th>
        <th>Giá</th>
        <th>Ảnh</th>
        <th>Hành động</th>
    </tr>

    <?php foreach ($tours as $t): ?>
    <tr>
        <td><?= $t['id']; ?></td>
        <td><?= $t['name']; ?></td>
        <td><?= $t['type']; ?></td>
        <td><?= number_format($t['price']); ?>đ</td>
        <td><img src="<?= $t['image']; ?>" width="90"></td>

        <td>
            <a href="index.php?controller=tour&action=detail&id=<?= $t['id']; ?>">Chi tiết</a> |
            <a href="index.php?controller=tour&action=edit&id=<?= $t['id']; ?>">Sửa</a> |
            <a href="index.php?controller=tour&action=delete&id=<?= $t['id']; ?>"
               onclick="return confirm('Xóa thật chứ?')">
               Xóa
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
