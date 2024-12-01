let isChatOpen = false;
let previousCount = 0;

function checkUnreadMessages() {
    fetch("../includes/check_unread_messages.php")
        .then(response => response.json())
        .then(data => {
            const unreadCountElement = document.getElementById("unreadCount");
            unreadCountElement.textContent = data.unread_count;

            // إشعار للمستخدم عند وجود رسائل جديدة
            if (data.unread_count > previousCount && !isChatOpen) { // تحقق إذا كانت الدردشة مغلقة
                alert("لديك رسائل جديدة!");
            }

            previousCount = data.unread_count; // تحديث قيمة عداد الرسائل غير المقروءة
        })
        .catch(error => console.error("Error fetching unread messages:", error));
}

// استدعاء هذه الدالة عند فتح الدردشة
function openChat() {
    isChatOpen = true; // تعيين المتغير إلى صحيح عند فتح الدردشة
    updateUnreadCountToZero(); // تحديث عدد الرسائل غير المقروءة إلى صفر

    // ... تحميل الرسائل الأخرى
}

function updateUnreadCountToZero() {
    fetch("../includes/update_unread_count.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ /* هنا يمكنك إضافة أي بيانات إضافية إذا لزم الأمر */ })
    })
    .then(response => response.json())
    .then(data => {
        console.log("تم تحديث عدد الرسائل غير المقروءة إلى صفر");
    })
    .catch(error => console.error("Error updating unread count:", error));
}

// تحديث عدد الرسائل غير المقروءة كل 10 ثوانٍ
setInterval(checkUnreadMessages, 10000);
window.addEventListener("DOMContentLoaded", checkUnreadMessages);

