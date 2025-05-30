<?php

return [
    'name'                 => "اسم المجال",
    'description'          => "مع Domainnameapi.com ، أحد مسجلي أسماء النطاقات المشهورين ، يمكن إجراء جميع معاملات اسم المجال على الفور من خلال واجهة برمجة تطبيقات المجال. للقيام بذلك ، حدد معلومات حساب عميل domainnameapi.com الخاص بك في الحقول التالية.",
    'importTldButton'      => "استيراد أكثر من 750 تمديدًا",
    'hour'                 => "ساعة",
    'fields'               => [
        'balance'       => "توازن",
        'username'      => "اسم مستخدم الموزع",
        'password'      => "كلمة مرور الموزع",
        'test-mode'     => "وضع الاختبار",
        'privacyFee'    => "رسوم حماية Whois",
        'adp'           => "تحديث التسعير تلقائيًا",
        'importTld'     => "ملحقات الاستيراد",
        'cost-currency' => "عملة التكلفة",
        'api-v2'        => "استخدم النسخة الجديدة",
        'resellerid'    => "معرف الموزع",
        'periodic-sync' => "مزامنة دورية",
        'sync-count'    => "عدد المزامنات في الدقيقة",
        'sync-delay'    => "تكرار المزامنة",
    ],
    'desc'                 => [
        'privacyFee'    => "<br>اطلب رسومًا لخدمة حماية Whois.",
        'test-mode'     => 'تفعيل للمعالجة في وضع الاختبار',
        'adp'           => 'يسترد الأسعار تلقائيًا يوميًا، والسعر مضبوط على نسبة الربح',
        'importTld-1'   => 'استيراد جميع الامتدادات تلقائيًا',
        'importTld-2'   => 'سيتم استيراد جميع امتدادات النطاق والتكاليف المسجلة في واجهة برمجة التطبيقات بشكل جماعي.',
        'cost-currency' => '',
        'periodic-sync' => "يتم تشغيل حدث المزامنة كل دقيقة، إذا قمت بتفعيله، فإنه يعالج وفقًا للإعدادات الأخرى",
        'sync-count'    => "عدد النطاقات التي سيتم معالجتها في المزامنة، يمكنك اختيار رقم مرتفع إذا كان لديك العديد من أسماء النطاقات. يجب عليك اختيار أرقام صغيرة لتجنب الوصول إلى حدود المعدل.",
        'sync-delay'    => "تكرار مزامنة كل اسم نطاق. يُنصح بالحفاظ على التكرار طويلاً لتجنب الوصول إلى حدود المعدل. أيضًا، إذا حافظت عليه قصيرًا، فقد لا تتم مزامنة بعض النطاقات.",
    ],
    'tabDetail'            => "معلومات API",
    'tabImport'            => "استيراد",
    'testButton'           => "اختبار الاتصال",
    'importNote'           => "يمكنك بسهولة نقل أسماء النطاقات المسجلة بالفعل في نظام الموفر. يتم إنشاء أسماء النطاقات المستوردة كملحق ، ويتم تمييز أسماء النطاقات المسجلة حاليًا في النظام باللون الأخضر.",
    'importStartButton'    => "استيراد",
    'saveButton'           => "احفظ التغييرات",
    'error1'               => "معلومات API غير متوفرة",
    'error2'               => "معلومات المجال والامتداد غير موجودة",
    'error3'               => "حدث خطأ أثناء استرداد معرف جهة الاتصال",
    'error4'               => "فشل في الحصول على معلومات الحالة",
    'error5'               => "لا يمكن استرداد معلومات النقل",
    'error6'               => "بعد الانتهاء من معالجة موفر واجهة برمجة التطبيقات ، يمكنك تنشيط حالة الطلب",
    'error7'               => "PHP Soap غير مثبت على الخادم الخاص بك. اتصل بموفر الاستضافة الخاص بك لمزيد من المعلومات.",
    'error8'               => "يرجى إدخال معلومات API",
    'error9'               => "فشلت عملية الاستيراد",
    'error10'              => "حدث خطأ",
    'error11'              => "يجب أن يحتوي اسم الشركة على كلمتين على الأقل.",
    'error12'              => "يجب تقديم المستندات والتحقق قبل تفعيل اسم النطاق.",
    'success1'             => "تم حفظ الإعدادات بنجاح",
    'success2'             => "نجح اختبار الاتصال",
    'success3'             => "تم الاستيراد بنجاح",
    'success4'             => "تم استيراد الملحقات بنجاح",
    'version1'             => 'إصدارك الحالي:',
    'version2'             => 'يرجى التحديث.',
    'version3'             => 'صفحة التحديث',
    'version4'             => 'إصدارك محدث!',
    'headerImport'         => "سيتم استيراد أسماء النطاقات أدناه",
    'noImportDomains'      => "لم يتم العثور على أسماء نطاقات للاستيراد.",
    'importQuestion'       => " سيتم استيراد النطاق. هل أنت متأكد؟",
    'yes'                  => "نعم",
    'no'                   => "لا",
    'importProcessing'     => "عملية الاستيراد جارية...",
    'process'              => 'عملية',
    'importFinished'       => 'اكتملت عملية الاستيراد.',
    'okey'                 => 'حسناً',
    'tabImportTld'         => 'استيراد الامتدادات',
    'importTldNote'        => 'يمكنك اختيار واستيراد الامتدادات والتكاليف المسجلة في API بشكل جماعي. يتم حساب جميع التسعيرات بالدولار الأمريكي. لتعطيل المزامنة التلقائية، اختر خيار Excl(Exclude)',
    'tld'                  => 'امتداد',
    'dna'                  => 'DNA؟',
    'cost'                 => 'تكلفة',
    'current'              => 'بيع',
    'margin'               => 'ربح',
    'register'             => 'تسجيل',
    'renew'                => 'تجديد',
    'transfer'             => 'نقل',
    'noTldSelected'        => 'لم يتم اختيار أي TLD',
    'noTldSelectedDesc'    => 'يرجى اختيار TLD للاستيراد',
    'numofTLDSelected'     => ' أنت تقوم بمزامنة الامتداد، هل أنت متأكد؟',
    'numofTLDSynced'       => 'تم الانتهاء من مزامنة الامتداد',
    'numofTLDSyncedTxt'    => 'اكتملت العملية بنجاح',
    'numofTLDNotSynced'    => 'خطأ',
    'numofTLDNotSyncedTxt' => 'حدث خطأ. يرجى المحاولة مرة أخرى.',
    'stillProcessing'      => 'العملية مستمرة...',
    'eplasedTime'          => 'استغرقت اتصال API :time: ثانية',
];
