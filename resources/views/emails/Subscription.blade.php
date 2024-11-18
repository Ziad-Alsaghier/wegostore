<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th style="text-align: left; padding: 10px; border: 1px solid #ddd;">المعلومات</th>
            <th style="text-align: right; padding: 10px; border: 1px solid #ddd;">التفاصيل</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;">{{$data['users']['name']}}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">الاسم</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;">{{$data['users']['phone']}}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">رقم الهاتف</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;">{{$data['users']['email']}}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">الايميل</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;">{{date('d-m-Y')}}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">التاريخ</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;">{{$data['plans']['name']}}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">الاشتراك</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;">{{$data['users']['expire_date']}}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">تاريخ انتهاء الاشتراك</td>
        </tr>
    </tbody>
</table>
