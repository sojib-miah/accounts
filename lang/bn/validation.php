<?php

return [
    'required' => ':attribute ফিল্ডটি পূরণ করতে হবে।',
    'email' => ':attribute অবশ্যই একটি সঠিক ইমেইল হতে হবে।',
    'unique' => 'এই :attribute ইতোমধ্যে ব্যবহার করা হয়েছে।',
    'min' => [
        'string' => ':attribute কমপক্ষে :min অক্ষরের হতে হবে।',
        'numeric' => ':attribute কমপক্ষে :min হতে হবে।',
    ],
    'max' => [
        'string' => ':attribute সর্বোচ্চ :max অক্ষরের হতে পারবে।',
        'numeric' => ':attribute সর্বোচ্চ :max হতে পারবে।',
    ],
    'confirmed' => ':attribute নিশ্চিতকরণ মিলছে না।',
    'exists' => 'নির্বাচিত :attribute সঠিক নয়।',

    'attributes' => [
        'name' => 'নাম',
        'email' => 'ইমেইল',
        'password' => 'পাসওয়ার্ড',
        'phone' => 'ফোন',
        'company_id' => 'কোম্পানি',
        'branch_id' => 'শাখা',
        'product_id' => 'পণ্য',
        'quantity' => 'পরিমাণ',
    ],
];