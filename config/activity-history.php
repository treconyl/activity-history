<?php

return [
    // Thêm các thư mục chứa các models để ghi log lại các hành động của người dùng
    'paths' => [
        base_path('app/Models'),
        base_path('Modules/Admin/Entities'),
    ],

    // Điều chỉnh đoạn này để phù hợp với cấu trúc role của dự án của bạn (bộ lọc hiệu quả hơn)
    'role_resolver' => function ($user) {
        return $user->role_id ?? 0;
    },

    // Thêm các mô hình cần loại trừ ở đây
    'excluded_models' => [
        App\Models\ActivityHistory::class, // mặc định phải loại trừ mô hình này tránh vòng lặp vô hạn
       
    ],
];