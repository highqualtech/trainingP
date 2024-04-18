<div style="width:229px;position:absolute;background:#4e5657;height:100%;z-index:1;margin-top:2px;"></div>
<div style="z-index:2;position:absolute;">
<div style="width:660px;padding:-1px 30px 30px 30px">
<table style="width:100%;">
    <tr>
        <td style="width:193px;vertical-align:top;text-align:center;padding-top:30px;"><img src="https://lms.hqtcloud.co.uk/img/logo2x.png" style="max-width:180px;"></td>
        <td style="vertical-align:top;font-size:16px;font-family:Arial, sans-serif;background:#f8f6f6;padding:50px;"><span style="font-size:24px;"></td>
    </tr>
</table>
</div>

    <div style="text-align:center;font-size:30px;font-family:Arial, sans-serif;"><br><strong>{{ $coursetitle }}</strong><br><br></div>
    <div style="text-align:center;font-size:24px;font-family:Arial, sans-serif;">Certificate of attendance and test in the course elements<br><br></div>
    <div style="text-align:center;font-size:20px;font-family:Arial, sans-serif;">for<br><br></div>
    <div style="text-align:center;font-size:30px;font-family:Arial, sans-serif;"><strong>{{ $staffname }}</strong><br><br></div>
    <div style="text-align:center;font-size:18px;font-family:Arial, sans-serif;">Comprising:
        <br><ul>
            @if($keypoint1!='')<li>{{ $keypoint1 }}</li>@endif
            @if($keypoint2!='')<li>{{ $keypoint2 }}</li>@endif
            @if($keypoint3!='')<li>{{ $keypoint3 }}</li>@endif
            @if($keypoint4!='')<li>{{ $keypoint4 }}</li>@endif
            @if($keypoint5!='')<li>{{ $keypoint5 }}</li>@endif
        </ul></div>
    <div style="text-align:center;font-size:16px;font-family:Arial, sans-serif;">On:<br><br></div>
    <div style="text-align:center;font-size:20px;font-family:Arial, sans-serif;"><strong>{{ $completedate }}</strong><br><br></div>
    <div style="text-align:center;font-size:16px;font-family:Arial, sans-serif;">Assessed on this day and achieved the required standard.<br><br></div>
    <div style="text-align:center;font-size:16px;font-family:Arial, sans-serif;">Training carried out by<br><br></div>
    <div style="text-align:center;font-size:18px;font-family:Arial, sans-serif;"><strong>{{ $instructor }}</strong><br><br></div>


</div>

