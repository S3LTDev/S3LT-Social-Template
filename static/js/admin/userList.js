function getUserList() {
    const url = "../api.admin.userlist"
    $.getJSON(url, function(data) {
        if (data.status == 'success') {
            $('#user_list').empty()
            $.each(data.users, function(key, val) {
                let user_item = 
                    `<tr>
                        <td class="avatar"><img src='${val['user_avatar']}' class='table-avatar'/></td>
                        <td>${val['user_id']}</td>
                        <td>${val['user_name']}</td>
                        <td>${nDetailFormatter(val['user_gems'])}</td>
                        <td>${val['admin']}</td>
                        <td>
                            <a href='/admin.userdetail/${val['user_id']}' class='btn btn-success bold' style='display: inline-flex !important;'>
                                <i data-feather="edit" style='width:18px;height:18px;'></i>
                                <div style='display: inline; margin-left: 5px; margin-top: 1px;'>Edit</div>
                            </a>
                            <a href='#' onclick='changeUser("${val['user_id']}", ${val['user_suspend']})' class='btn btn-${(val['user_suspend'] == 1) ? 'primary' : 'danger'} bold' 
                                style='display: inline-flex !important;'>
                                <i data-feather="${(val['user_suspend'] == 1) ? 'check-circle' : 'x-circle'}" style='width:18px;height:18px;'></i>
                                <div style='display: inline; margin-left: 5px; margin-top: 0px;'>${(val['user_suspend'] == 1) ? "UnSuspend" : "Suspend"}</div>
                            </a>
                        </td>
                    </tr>`
                $('#user_list').append(user_item)
            })
            feather.replace()
        }
    })
}

function changeUser(userId, suspend_flag) {
    let url = ""
    if ( suspend_flag == 0 )
        url = "../api.admin.suspend/" + userId
    else
        url = "../api.admin.unsuspend/" + userId
        
    if ( confirm("Are you sure?") ) 
        $.getJSON(url, function(data) {
            if (data.status == 'success')
                getUserList()
            else 
                alert('Suspending/Unsuspending User is failed.')
        })
}

document.addEventListener('DOMContentLoaded', function() {
    getUserList()

    setInterval(function() {
        getUserList()
    }, 30000)
})