<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc các bảng jobs, job_batches, và failed_jobs.
     *
     * @return void
     */
    public function up(): void
    {
        // Bảng lưu trữ các công việc hàng đợi (jobs)
        Schema::create('jobs', function (Blueprint $table) {
            $table->id(); // ID công việc (Khóa chính)
            $table->string('queue')->index(); // Tên hàng đợi phân loại công việc
            $table->longText('payload'); // Dữ liệu payload của công việc được chuỗi hóa
            $table->unsignedTinyInteger('attempts'); // Số lần đã thử thực hiện lại công việc
            $table->unsignedInteger('reserved_at')->nullable(); // Thời điểm bắt đầu xử lý công việc
            $table->unsignedInteger('available_at'); // Thời điểm công việc sẵn sàng để thực thi
            $table->unsignedInteger('created_at'); // Thời điểm tạo công việc
        });

        // Bảng lưu trữ thông tin thực thi theo lô (job_batches)
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary(); // ID lô công việc (Khóa chính)
            $table->string('name'); // Tên lô công việc
            $table->integer('total_jobs'); // Tổng số lượng công việc trong lô
            $table->integer('pending_jobs'); // Số lượng công việc còn chờ xử lý
            $table->integer('failed_jobs'); // Số lượng công việc bị lỗi
            $table->longText('failed_job_ids'); // Danh sách ID các công việc thất bại
            $table->mediumText('options')->nullable(); // Các tùy chọn cấu hình bổ sung
            $table->integer('cancelled_at')->nullable(); // Thời điểm hủy lô công việc
            $table->integer('created_at'); // Thời điểm tạo lô công việc
            $table->integer('finished_at')->nullable(); // Thời điểm hoàn thành toàn bộ lô công việc
        });

        // Bảng ghi nhận các công việc bị thực hiện thất bại (failed_jobs)
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id(); // ID bản ghi (Khóa chính)
            $table->string('uuid')->unique(); // UUID duy nhất của công việc lỗi
            $table->text('connection'); // Kết nối hàng đợi của công việc
            $table->text('queue'); // Tên hàng đợi chứa công việc lỗi
            $table->longText('payload'); // Dữ liệu payload công việc lỗi
            $table->longText('exception'); // Chi tiết thông tin ngoại lệ lỗi gặp phải
            $table->timestamp('failed_at')->useCurrent(); // Thời điểm công việc bị lỗi (Mặc định hiện tại)
        });
    }

    /**
     * Hủy bỏ các bảng jobs, job_batches và failed_jobs khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
