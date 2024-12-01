// التحقق من تحميل مكتبة OneSignal وتكوين الإعدادات الأساسية
window.OneSignalDeferred = window.OneSignalDeferred || [];
OneSignalDeferred.push(async function (OneSignal) {
    try {
        // تهيئة OneSignal
        await OneSignal.init({
            appId: "99e1b7d4-ffe5-os_v2_app_thq3pvh74vbohe2l5qxgftv3zflrax6faozuhlupuevvdxijjwxgkwcby22sttz73bsbsmothq5taqygbktxl7rhi2dd6dnf7fccydi-934b-ec2e62cebbc9", // استبدل بـ App ID الخاص بك
            notifyButton: {
                enable: true, // إظهار زر الاشتراك في الإشعارات
                position: "bottom-right", // موضع الزر
            },
            welcomeNotification: {
                title: "مرحبًا بك!",
                message: "تم الاشتراك بنجاح في الإشعارات!",
                url: "/", // الرابط الذي يتم فتحه عند النقر على الإشعار
                icon: "../img", // استبدل بـ مسار أيقونة الإشعار
            },
        });

        console.log("تم تهيئة OneSignal بنجاح.");

        // التحقق من حالة الاشتراك
        const isSubscribed = await OneSignal.isPushNotificationsEnabled();
        if (isSubscribed) {
            console.log("المستخدم مشترك بالفعل في الإشعارات.");
        } else {
            console.log("المستخدم غير مشترك في الإشعارات.");
        }

        // إرسال Tag لتعريف المستخدم بناءً على معرفه
        const userId = "<?= $_SESSION['user_id']; ?>"; // جلب معرف المستخدم من PHP
        if (userId) {
            await OneSignal.sendTag("user_id", userId);
            console.log(`تم تعيين Tag للمستخدم: ${userId}`);
        }

        // التعامل مع الإشعارات المرسلة عند النقر
        OneSignal.on("notificationDisplay", function (event) {
            console.log("تم عرض الإشعار:", event);
        });

        OneSignal.on("notificationDismiss", function (event) {
            console.log("تم تجاهل الإشعار:", event);
        });

        // إعداد رد فعل للنقر على الإشعار
        OneSignal.on("notificationClick", function (event) {
            console.log("تم النقر على الإشعار:", event);
            if (event.data && event.data.url) {
                window.open(event.data.url, "_blank");
            }
        });
    } catch (error) {
        console.error("حدث خطأ أثناء تهيئة OneSignal:", error);
    }
});
