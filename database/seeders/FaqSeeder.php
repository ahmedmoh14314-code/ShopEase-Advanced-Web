<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question'   => 'What payment methods do you accept?',
                'answer'     => 'We currently accept Cash on Delivery (COD). You pay in cash when your order is delivered to your address.',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'question'   => 'How long does delivery take?',
                'answer'     => 'Orders are usually delivered within 2-5 business days depending on your city. You can track the status of your order from the My Orders page.',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'question'   => 'Can I return a product?',
                'answer'     => 'Yes. You can request a return within 14 days of receiving your order, as long as the product is unused and in its original packaging.',
                'sort_order' => 3,
                'is_active'  => true,
            ],
            [
                'question'   => 'Do I need an account to place an order?',
                'answer'     => 'Yes, you need to register and log in so we can save your cart, process checkout, and let you follow your orders.',
                'sort_order' => 4,
                'is_active'  => true,
            ],
            [
                'question'   => 'How do I change the quantity of an item in my cart?',
                'answer'     => 'Open the Cart page and use the quantity controls next to each item. The total is updated automatically.',
                'sort_order' => 5,
                'is_active'  => true,
            ],
            [
                'question'   => 'Will online card payment be available?',
                'answer'     => 'Online card payment is planned for a future update. For now, Cash on Delivery is the only available option.',
                'sort_order' => 6,
                'is_active'  => false,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
