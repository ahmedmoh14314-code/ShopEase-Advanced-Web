<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    public function run(): void
    {
        $messages = [
            [
                'name'       => 'John Smith',
                'email'      => 'john.smith@mail.com',
                'subject'    => 'Product Inquiry',
                'message'    => "Hello, I'm interested in the Smart Watch Series 8. Do you have it available in rose gold or only the standard silver? Also, what's the shipping time to Ankara? Thank you.",
                'is_read'    => false,
                'created_at' => '2026-05-17 14:22:00',
                'updated_at' => '2026-05-17 14:22:00',
            ],
            [
                'name'       => 'Olivia Brown',
                'email'      => 'olivia@mail.com',
                'subject'    => 'Shipping Question',
                'message'    => "Hi, how long does delivery typically take to Izmir? I placed an order last week and haven't received any tracking info yet.",
                'is_read'    => false,
                'created_at' => '2026-05-16 09:48:00',
                'updated_at' => '2026-05-16 09:48:00',
            ],
            [
                'name'       => 'James Taylor',
                'email'      => 'james.t@mail.com',
                'subject'    => 'Return Request',
                'message'    => "I'd like to return the Wireless Headphones I ordered last week. Could you walk me through the return process and how to get the refund?",
                'is_read'    => true,
                'created_at' => '2026-05-15 18:03:00',
                'updated_at' => '2026-05-15 18:03:00',
            ],
            [
                'name'       => 'Sophia Davis',
                'email'      => 'sophia.d@mail.com',
                'subject'    => 'Bulk Order Inquiry',
                'message'    => "Hi, I'm interested in purchasing 20 pairs of the Wireless Headphones for our office. Is there a bulk discount available?",
                'is_read'    => false,
                'created_at' => '2026-05-15 11:10:00',
                'updated_at' => '2026-05-15 11:10:00',
            ],
            [
                'name'       => 'William Moore',
                'email'      => 'wmoore@mail.com',
                'subject'    => 'Payment Question',
                'message'    => 'Do you only accept Cash on Delivery? I prefer to pay by credit card. Will online payment be available in the future?',
                'is_read'    => true,
                'created_at' => '2026-05-14 16:55:00',
                'updated_at' => '2026-05-14 16:55:00',
            ],
            [
                'name'       => 'Emma Wilson',
                'email'      => 'emma.w@mail.com',
                'subject'    => 'General Support',
                'message'    => "I can't access my account - the password reset email isn't coming through. Could someone please help me get back in?",
                'is_read'    => false,
                'created_at' => '2026-05-13 10:34:00',
                'updated_at' => '2026-05-13 10:34:00',
            ],
        ];

        foreach ($messages as $m) {
            $createdAt = $m['created_at'];
            $updatedAt = $m['updated_at'];
            unset($m['created_at'], $m['updated_at']);

            $message = ContactMessage::create($m);
            $message->created_at = $createdAt;
            $message->updated_at = $updatedAt;
            $message->save();
        }
    }
}
