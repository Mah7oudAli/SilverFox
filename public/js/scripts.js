// js/scripts.js

document.getElementById('message-form').addEventListener('submit', function(event) {
    event.preventDefault(); // منع تحديث الصفحة

    var message = document.getElementById('message').value;
    var employeeId = document.getElementById('employee_id').value;

    if (message.trim() === '') {
        return; // لا ترسل رسالة فارغة
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../includes/send_message.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status === 200) {
            document.getElementById('message').value = ''; // إعادة تعيين حقل الرسالة
            loadMessages(); // جلب الرسائل المحدثة
        }
    };
    xhr.send('message=' + encodeURIComponent(message) + '&employee_id=' + encodeURIComponent(employeeId));
});

function loadMessages() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../includes/get_messages.php', true);
    xhr.onload = function() {
        if (this.status === 200) {
            document.getElementById('chat-box').innerHTML = this.responseText;
            // تمرير إلى الأسفل بعد تحميل الرسائل
            var chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight; // تمرير إلى الأسفل
        }
    };
    xhr.send();
}


// جلب الرسائل كل 5 ثوانٍ
setInterval(loadMessages, 5000);
loadMessages();
