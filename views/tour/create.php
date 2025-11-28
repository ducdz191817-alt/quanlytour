<?php include './src/views/layouts/home.php'; ?>

<h2>Thêm Tour mới</h2>

<form method="post" enctype="multipart/form-data" action="">
    <div>
        <label>Tên tour</label><br>
        <input type="text" name="name" required>
    </div>

    <div>
        <label>Loại tour</label><br>
        <select name="type">
            <option value="Trong nước">Trong nước</option>
            <option value="Quốc tế">Quốc tế</option>
            <option value="Theo yêu cầu">Theo yêu cầu</option>
        </select>
    </div>

    <div>
        <label>Giá</label><br>
        <input type="number" name="price" min="0" value="0">
    </div>

    <div>
        <label>Lịch trình (JSON hoặc text)</label><br>
        <textarea name="schedule" rows="5" placeholder='VD: [{"day":1,"note":"Hà Nội - Sapa"}, {"day":2,"note":"Hiking"}]'></textarea>
    </div>

    <div>
        <label>Chính sách</label><br>
        <textarea name="policy" rows="4"></textarea>
    </div>

    <div>
        <label>Nhà cung cấp</label><br>
        <input type="text" name="supplier">
    </div>

    <div>
        <label>Ảnh (upload)</label><br>
        <input type="file" name="image" accept="image/*">
    </div>

    <div>
        <button type="submit">Lưu</button>
        <a href="index.php?controller=tour&action=index">Hủy</a>
    </div>
</form>
