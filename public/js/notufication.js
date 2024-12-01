// // طلب الإذن لعرض الإشعارات
// if (Notification.permission !== "granted") {
//     Notification.requestPermission();
// }

// // وظيفة لإظهار إشعار المتصفح
// function showNotification(title, message) {
//     if (Notification.permission === "granted") {
//         new Notification(title, {
//             body: message,
//             icon: "../img/new-message.svg", // ضع أيقونة مخصصة للإشعار
//         });
//     }
// }

// // تحميل عدد الرسائل غير المقروءة وتحديث واجهة المستخدم
// // وظيفة لتحميل عدد الرسائل غير المقروءة
// // وظيفة لتحميل عدد الرسائل غير المقروءة
// // وظيفة لتحميل عدد الرسائل غير المقروءة
// function checkUnreadMessages() {
//     $.ajax({
//         url: '../includes/check_unread_messages.php', // ملف PHP لجلب عدد الرسائل غير المقروءة
//         method: 'POST',
//         data: { client_id: currentClientId, employee_id: currentEmployeeId },
//         dataType: 'json',
//         success: function(response) {
//             if (response.status === 'success') {
//                 const unreadCount = response.unread_count;

//                 // تحديث العدد في الـ badge
//                 const unreadBadge = $('#unreadCount');
//                 unreadBadge.text(unreadCount); // عرض العدد
//             } else {
//                 console.error(response.message);
//             }
//         },
//         error: function(xhr, status, error) {
//             console.error('Error fetching unread messages:', error);
//         }
//     });
// }

// // وظيفة لتصفير العداد عند فتح الدردشة
// function markMessagesAsRead() {
//     $.ajax({
//         url: '../includes/mark_messages_as_read.php', // ملف PHP لتحديث حالة الرسائل
//         method: 'POST',
//         data: { client_id: currentClientId, employee_id: currentEmployeeId },
//         success: function(response) {
//             if (response.status === 'success') {
//                 $('#unreadCount').text('0'); // تصفير العداد
//             } else {
//                 console.error(response.message);
//             }
//         },
//         error: function(xhr, status, error) {
//             console.error('Error marking messages as read:', error);
//         }
//     });
// }

// // استدعاء الوظيفة عند تحميل الصفحة
// $(document).ready(function() {
//     checkUnreadMessages(); // تحديث العدد عند تحميل الصفحة

//     // تحديث العدد كل 3 ثوانٍ
//     setInterval(checkUnreadMessages, 3000);

//     // تصفير العداد عند فتح نافذة الدردشة
//     $('#chatButton').on('click', function() {
//         markMessagesAsRead();
//     });
// });
new Noty({
    text: 'لديك رسالة جديدة!',
    type: 'success',
    timeout: 3000
}).show();
