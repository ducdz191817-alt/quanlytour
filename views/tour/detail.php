<?php include './src/views/layouts/home.php'; ?>

<h2>Chi tiết Tour: <?= htmlspecialchars($tour['name']); ?></h2>

<div>
    <strong>Loại:</strong> <?= htmlspecialchars($tour['type']); ?>
</div>
<div>
    <strong>Giá:</strong> <?= number_format($tour['price']); ?> đ
</div>

<div>
    <strong>Nhà cung cấp:</strong> <?= htmlspecialchars($tour['supplier']); ?>
</div>

<div>
    <strong>Ảnh:</strong><br>
    <?php if (!empty($tour['image'])): ?>
        <img src="<?= htmlspecialchars($tour['image']); ?>" width="300" alt="Ảnh tour">
    <?php else: ?>
        <span>Không có ảnh</span>
    <?php endif; ?>
</div>

<div>
    <h3>Lịch trình</h3>
    <pre style="white-space:pre-wrap; background:#f5f5f5; padding:8px;"><?= htmlspecialchars($tour['schedule']); ?></pre>
    <p><em>Bạn có thể lưu lịch trình dạng JSON (mảng ngày) hoặc plain text.</em></p>
</div>

<div>
    <h3>Chính sách</h3>
    <p><?= nl2br(htmlspecialchars($tour['policy'])); ?></p>
</div>

<div>
    <a href="index.php?controller=tour&action=edit&id=<?= $tour['id']; ?>">Sửa</a> |
    <a href="index.php?controller=tour&action=delete&id=<?= $tour['id']; ?>" onclick="return confirm('Xóa tour này?')">Xóa</a> |
    <a href="index.php?controller=tour&action=index">Về danh sách</a>
</div>
