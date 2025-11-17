<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_management.php"; ?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">⚠️ Xác nhận kết thúc</div>
    </div>

    <div class="card">
        <div class="card-body text-center">
            <div class="alert alert-warning">
                <h4>⚠️ Bạn có chắc muốn kết thúc sửa điểm?</h4>
                <p>Các thay đổi chưa lưu sẽ bị mất.</p>
            </div>
            
            <form method="POST" action="index.php?controller=scoreEdit&action=index">
                <button type="submit" name="confirm" value="1" class="btn btn-danger btn-lg">
                    ✅ Xác nhận kết thúc
                </button>
                <button type="button" class="btn btn-secondary btn-lg" 
                        onclick="window.location.href='index.php?controller=scoreEdit&action=index'">
                    ❌ Quay lại sửa điểm
                </button>
            </form>
        </div>
    </div>
</div>

<?php include "views/layout/footer.php"; ?>