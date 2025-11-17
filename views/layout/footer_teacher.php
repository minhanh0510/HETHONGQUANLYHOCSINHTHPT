</div> <!-- End main-content -->
</div> <!-- End wrapper -->

<footer class="footer">
    <div class="footer-content">
        <p>&copy; <?= date('Y') ?>  © 2025 Hệ thống Quản lý Giáo dục | Thiết kế bởi Nhóm 2 - TH3.</p>
    </div>
</footer>

<style>
.footer {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: #ffffff;
    padding: 20px 0;
    margin-top: 40px;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    text-align: center;
    padding: 0 20px;
}

.footer-content p {
    margin: 5px 0;
    font-size: 14px;
    opacity: 0.9;
}

.footer-content p:first-child {
    font-weight: 600;
    font-size: 15px;
}

@media (max-width: 768px) {
    .footer {
        padding: 15px 0;
        margin-top: 30px;
    }
    
    .footer-content p {
        font-size: 13px;
    }
}
</style>

</body>
</html>