<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactSubmitTest extends TestCase
{
    use RefreshDatabase;

    // Test gửi liên hệ thành công lưu vào db
    public function test_visitor_can_submit_contact_form()
    {
        $contactData = [
            'name' => 'Nguyễn Văn QC',
            'email' => 'nguyenvanqc@gmail.com',
            'phone' => '0901234567',
            'subject' => 'Góp ý chất lượng giao hàng',
            'message' => 'Giao hàng rất nhanh, tuy nhiên nên bọc gói kỹ hơn các sản phẩm tươi sống để giữ lạnh lâu hơn.',
        ];

        // Gửi request POST
        $response = $this->post(route('contact.store'), $contactData);

        // Kiểm tra chuyển hướng và session thành công
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Gửi liên hệ thành công! Chúng tôi sẽ xử lý và phản hồi bạn sớm nhất.');

        // Kiểm tra db có lưu bản ghi hay không
        $this->assertDatabaseHas('contacts', [
            'name' => 'Nguyễn Văn QC',
            'email' => 'nguyenvanqc@gmail.com',
            'phone' => '0901234567',
            'subject' => 'Góp ý chất lượng giao hàng',
            'message' => 'Giao hàng rất nhanh, tuy nhiên nên bọc gói kỹ hơn các sản phẩm tươi sống để giữ lạnh lâu hơn.',
            'status' => 'pending',
        ]);
    }

    // Test kiểm tra validate khi để trống form
    public function test_contact_form_requires_validation()
    {
        $response = $this->post(route('contact.store'), []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }
}
