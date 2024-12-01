let isMessageSending = false; // علم لتحديد حالة إرسال الرسالة

// تحميل الرسائل الخاصة بالموظف المرتبط
function loadMessages() {
    if (currentEmployeeId && !isMessageSending) { // إذا لم يكن هناك إرسال رسالة حاليًا
        $.ajax({
            url: '../includes/client_messages_display.php',
            type: 'POST',
            data: {
                employee_id: currentEmployeeId
            },
            success: function(data) {
                $('#chat-messages').html(data);

                // ضبط موضع التمرير ليبقى في الأسفل
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error loading messages:', textStatus, errorThrown);
            }
        });
    }
}

// إرسال رسالة جديدة
$('#send-message').on('click', function() {
    let message = $('#message-input').val();
    if (message && currentEmployeeId) {
        isMessageSending = true; // تعيين حالة إرسال الرسالة

        // إخفاء قسم رفع التقرير
        $('#report-upload-section').hide();

        $.ajax({
            url: '../includes/client_send_message.php',
            type: 'POST',
            data: {
                message: message,
                employee_id: currentEmployeeId
            },
            success: function() {
                $('#message-input').val(''); // تفريغ حقل الرسالة بعد الإرسال
                loadMessages(); // إعادة تحميل الرسائل بعد الإرسال

                // إظهار القسم بعد فترة قصيرة
                setTimeout(showReportUploadSection, 10000); // سيتم إظهار القسم بعد 10 ثوانٍ
            },
            complete: function() {
                isMessageSending = false; // إلغاء حالة الإرسال
            }
        });
    }
});

// دالة لإظهار قسم رفع التقرير
function showReportUploadSection() {
    $('#report-upload-section').show(); // إظهار قسم رفع التقرير
}

// تحديث تلقائي للرسائل كل 3 ثوانٍ إذا لم يكن هناك إرسال رسالة
setInterval(loadMessages, 3000);
