<?php

namespace App\Http\Controllers\Admin\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

trait HandlesCrudSafety
{
    protected function validateCrud(Request $request, array $rules, array $attributes = []): array
    {
        return $request->validate($rules, $this->crudValidationMessages(), $this->crudAttributes($attributes));
    }

    protected function crudValidationMessages(): array
    {
        return [
            'required' => 'Vui lòng nhập :attribute trước khi lưu.',
            'array' => ':Attribute không đúng định dạng. Vui lòng tải lại trang và thử lại.',
            'min' => ':Attribute không được nhỏ hơn :min.',
            'max' => ':Attribute không được vượt quá :max ký tự.',
            'integer' => ':Attribute phải là số nguyên.',
            'numeric' => ':Attribute phải là số hợp lệ.',
            'boolean' => ':Attribute không đúng định dạng bật/tắt.',
            'date' => ':Attribute phải là ngày hợp lệ.',
            'after_or_equal' => ':Attribute phải sau hoặc bằng :date.',
            'exists' => ':Attribute không tồn tại hoặc đã bị xóa. Vui lòng tải lại trang.',
            'unique' => ':Attribute này đã tồn tại. Vui lòng dùng giá trị khác để tránh trùng dữ liệu.',
            'in' => ':Attribute không hợp lệ. Vui lòng chọn lại từ danh sách.',
            'image' => ':Attribute phải là tệp hình ảnh hợp lệ.',
            'mimes' => ':Attribute chỉ hỗ trợ các định dạng: :values.',
            'url' => ':Attribute phải là đường dẫn hợp lệ.',
        ];
    }

    protected function crudAttributes(array $attributes = []): array
    {
        return array_merge([
            'name' => 'tên',
            'title' => 'tiêu đề',
            'code' => 'mã',
            'sku' => 'SKU',
            'category_id' => 'danh mục',
            'category' => 'danh mục',
            'description' => 'mô tả',
            'icon' => 'biểu tượng',
            'sort_order' => 'thứ tự hiển thị',
            'is_active' => 'trạng thái hiển thị',
            'status' => 'trạng thái',
            'role_id' => 'vai trò',
            'phone' => 'số điện thoại',
            'address' => 'địa chỉ',
            'brand' => 'thương hiệu',
            'price' => 'giá bán',
            'original_price' => 'giá gốc',
            'stock' => 'tồn kho',
            'image_url' => 'đường dẫn ảnh',
            'product_image' => 'ảnh sản phẩm',
            'discount_type' => 'loại giảm giá',
            'discount_value' => 'giá trị giảm giá',
            'minimum_order' => 'đơn tối thiểu',
            'usage_limit' => 'giới hạn lượt dùng',
            'starts_at' => 'ngày bắt đầu',
            'ends_at' => 'ngày kết thúc',
            'question' => 'câu hỏi',
            'answer' => 'câu trả lời',
            'image' => 'hình ảnh',
            'link' => 'đường dẫn',
            'position' => 'vị trí hiển thị',
            'start_date' => 'ngày bắt đầu',
            'end_date' => 'ngày kết thúc',
            'is_approved' => 'trạng thái duyệt',
            '_record_updated_at' => 'phiên chỉnh sửa',
        ], $attributes);
    }

    protected function recordVersion(Model $model): string
    {
        return (string) ($model->updated_at?->getTimestamp() ?? '');
    }

    protected function assertFreshRecord(Request $request, Model $model, string $recordName = 'bản ghi'): void
    {
        if (! $request->filled('_record_updated_at')) {
            throw ValidationException::withMessages([
                '_record_updated_at' => 'Phiên chỉnh sửa không còn hợp lệ. Vui lòng tải lại trang rồi thao tác lại để tránh ghi đè dữ liệu cũ.',
            ]);
        }

        if (! hash_equals($this->recordVersion($model), (string) $request->input('_record_updated_at'))) {
            throw ValidationException::withMessages([
                '_record_updated_at' => "Dữ liệu {$recordName} đã thay đổi ở nơi khác. Vui lòng tải lại trang để xem bản mới nhất trước khi lưu.",
            ]);
        }
    }

    protected function runCrudOperation(Closure $operation, string $action = 'lưu'): RedirectResponse
    {
        try {
            return $operation();
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (QueryException $exception) {
            report($exception);

            return back()
                ->withInput()
                ->withErrors(['database' => $this->friendlyDatabaseMessage($exception, $action)]);
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->withErrors(['system' => "Không thể {$action} dữ liệu lúc này. Vui lòng tải lại trang và thử lại."]);
        }
    }

    protected function lockForCrud(Model $model): Model
    {
        return $model->newQuery()->lockForUpdate()->findOrFail($model->getKey());
    }

    private function friendlyDatabaseMessage(QueryException $exception, string $action): string
    {
        $sqlState = (string) ($exception->errorInfo[0] ?? $exception->getCode());
        $driverCode = (string) ($exception->errorInfo[1] ?? '');
        $message = $exception->getMessage();

        if ($sqlState === '23000' || in_array($driverCode, ['19', '1062'], true) || str_contains($message, 'UNIQUE')) {
            return 'Dữ liệu này đã tồn tại. Vui lòng kiểm tra mã, tên hoặc đường dẫn để tránh tạo bản ghi trùng.';
        }

        if (in_array($driverCode, ['1451', '1452'], true) || str_contains($message, 'FOREIGN KEY')) {
            return 'Không thể thao tác vì dữ liệu đang liên kết với bản ghi khác. Vui lòng kiểm tra lại trước khi xóa hoặc sửa.';
        }

        return "Không thể {$action} dữ liệu do hệ thống phát hiện xung đột. Vui lòng tải lại trang và thử lại.";
    }

    protected function transaction(Closure $operation): mixed
    {
        return DB::transaction($operation);
    }
}
