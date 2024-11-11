<?php
session_start();
session_unset();
session_destroy();
header("Location: index.html"); // เปลี่ยนเป็นหน้าที่คุณต้องการให้ผู้ใช้ไปหลังจากออกจากระบบ
exit();
