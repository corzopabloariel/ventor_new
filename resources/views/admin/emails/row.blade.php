<tr>
    <td>{{date("d/m/Y H:i", strtotime($email->updated_at))}}</td>
    <td>{!!($email->sent && !$email->error ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>')!!}</td>
    <td>{{($email->is_order ? 'SI' : 'NO')}}</td>
    <td>{{$email->type}}</td>
    <td>{{$email->ip}}</td>
    <td>{{$email->from}}</td>
    <td>{{implode(', ', $email->to)}}</td>
    <td>{{$email->subject}}</td>
    <td></td>
</tr>