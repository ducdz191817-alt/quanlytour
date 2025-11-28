<?php include './src/views/layouts/home.php'; ?>

<h2>Sửa Tour #<?= htmlspecialchars($tour['id']); ?></h2>

<form method="post" enctype="multipart/form-data" action="">
    <div>
        <label>Tên tour</label><br>
        <input type="text" name="name" required value="<?= htmlspecialchars($tour['name']); ?>">
    </div>

    <div>
        <label>Loại tour</label><br>
        <select name="type">
            <option value="Trong nước" <?= $tour['type']=='Trong nước' ? 'selected' : '' ?>>Trong nước</option>
            <option value="Quốc tế" <?= $tour['type']=='Quốc tế' ? 'selected' : '' ?>>Quốc tế</option>
            <option value="Theo yêu cầu" <?= $tour['type']=='Theo yêu cầu' ? 'selected' : '' ?>>Theo yêu cầu</option>
        </select>
    </div>

    <div>
        <label>Giá</label><br>
        <input type="number" name="price" min="0" value="<?= htmlspecialchars($tour['price']); ?>">
    </div>

    <div>
        <label>Lịch trình</label><br>
        <textarea name="schedule" rows="5"><?= htmlspecialchars($tour['schedule']); ?></textarea>
    </div>

    <div>
        <label>Chính sách</label><br>
        <textarea name="policy" rows="4"><?= htmlspecialchars($tour['policy']); ?></textarea>
    </div>

    <div>
        <label>Nhà cung cấp</label><br>
        <input type="text" name="supplier" value="<?= htmlspecialchars($tour['supplier']); ?>">
    </div>

    <div>
        <label>Ảnh hiện tại</label><br>
        <?php if (!empty($tour['image'])): ?>
            <img src="<?= htmlspecialchars($tour['image']); ?>" width="150" alt="Ảnh tour">
        <?php else: ?>
            <span>Chưa có ảnh</span>
        <?php endif; ?>
    </div>

    <div>
        <label>Thay ảnh mới (nếu muốn)</label><br>
        <input type="file" name="image" accept="image/*">
    </div>

    <div>
        <button type="submit">Cập nhật</button>
        <a href="index.php?controller=tour&action=index">Hủy</a>
    </div>
</form>
