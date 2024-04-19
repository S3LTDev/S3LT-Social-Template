/*  
@@@@@@ @@@@@@  @@@      @@@@@@@      @@@@@@@  @@@@@@@@ @@@  @@@
@@         @@! @@!        @@!        @@!  @@@ @@!      @@!  @@@
!@@!!   @!!!:  @!!        @!!        @!@  !@! @!!!:!   @!@  !@!
   !:!     !!: !!:        !!:        !!:  !!! !!:       !: .:! 
::.: :  ::: ::  : ::.: :    :         :: :  :  : :: :::    ::   

link  https://github.com/S3LTDev/S3LT-Social-Template
author  max2tz https://github.com/S3LT
license  GPL-3.0 License
*/
let _userAvatar = ''
let _userName = ''

function updateGems( addFlag ) {
    let originGems = parseInt($("#gems span").attr('title'))
    let changeGems = parseInt($("#gems_val").val())
    if ( originGems > 0 && changeGems > 0 ) {
        if (addFlag)
            originGems += changeGems
        else
            originGems -= changeGems
    }
        
    const url = `../api.admin.updateGems/${_userId}&${originGems}`
    $.getJSON(url, function(data) {
        if (data.status == 'success') {
            $("#gems span").attr('title', originGems)
            $("#gems span").html(nFormatter(originGems))
        }
    })
}

function htmlBadge( data ) {
    return `<tr id='badge_${data.id}'>
                <td><div><span title='${data.badge}'>${data.badge}</span><div><td>
                <td><a href='#' onclick='removeBadge(${data.id})'>Remove</a></td>
            </tr>`
}

function addBadge() {
    let template = [
        {
            bind: 'badge_str',
            type: 'text',
            label: 'Badge',
            labelPosition: 'left',
            labelWidth: '30%',
            align: 'left',
            width: '250px',
            required: true
        },
        {
            name: 'btnAddBadge',
            type: 'button',
            text: 'Add Badge',
            align: 'right',
            padding: {left: 0, top: 5, bottom: 5, right: 40}
        }
    ]

    let badgeForm = $('#badgeForm');

    badgeForm.jqxForm({
        template: template,
        value: [],
        padding: { left: 10, top: 10, right: 0, bottom: 10 }
    })

    let btn = badgeForm.jqxForm('getComponentByName', 'btnAddBadge')
    btn.on('click', function (event) {
        let form_val = badgeForm.val()
        if ( form_val['badge_str'] == "" )
            return;
        
        const url = `../api.admin.addBadge/${_userId}&${form_val['badge_str']}`
        if ( confirm("Are you sure?") ) {
            $('#badgeWindow').jqxWindow('close')

            $.getJSON(url, function(data) {
                if (data.status == 'success')
                    $('#badges table').append(htmlBadge({...data, badge: form_val['badge_str']}))
            })
        }
    })

    $('#badgeWindow').jqxWindow('open')
}

function removeBadge( id ) {
    const url = "../api.admin.removeBadge/" + id
    if ( confirm("Are you sure?") ) 
        $.getJSON(url, function(data) {
            if (data.status == 'success') $('#badge_' + id).remove()
        })
}

function htmlInventory( data ) {
    return `<div class="project-box-wrapper" id="inventory_${data.id}">
                <div class="project-box random-blue">
                    <div class="project-box-header">
                        <span>${data.name}</span>
                        <a href="#" onclick="removeInventory(${data.id})">Remove</a>
                    </div>
                    <div class="project-box-content-header">
                        <img class="inventory-img" src="../static/img/items/${data.asset}" alt="">
                    </div>
                    <div class="box-progress-wrapper">
                        <p class="box-progress-header">${data.rarity}</p>
                    </div>
                </div>
            </div>`
}

function addInventory() {
    let template = [
        {
            bind: 'name',
            type: 'text',
            label: 'Name',
            labelPosition: 'left',
            labelWidth: '30%',
            align: 'left',
            width: '250px',
            required: true
        },
        {
            bind: 'asset',
            type: 'text',
            label: 'Asset',
            labelPosition: 'left',
            labelWidth: '30%',
            align: 'left',
            width: '250px',
            required: false
        },
        {
            bind: 'rarity',
            type: 'text',
            label: 'Rarity',
            labelPosition: 'left',
            labelWidth: '30%',
            align: 'left',
            width: '250px',
            required: true
        },
        {
            bind: 'price',
            type: 'number',
            label: 'Price',
            labelPosition: 'left',
            labelWidth: '30%',
            align: 'left',
            width: '250px',
            required: true
        },
        {
            name: 'btnAddInventory',
            type: 'button',
            text: 'Add Inventory',
            align: 'right',
            padding: {left: 0, top: 5, bottom: 5, right: 40}
        }
    ]

    let inventoryForm = $('#inventoryForm')

    inventoryForm.jqxForm({
        template: template,
        value: [],
        padding: { left: 10, top: 10, right: 0, bottom: 10 }
    })

    let btn = inventoryForm.jqxForm('getComponentByName', 'btnAddInventory')
    btn.on('click', function (event) {
        let form_val = inventoryForm.val()

        if ( form_val['name'] == "" ) return
        
        const url = `../api.admin.addItem/${_userId}&${form_val['name']}&${form_val['asset']}&${form_val['rarity']}&${form_val['price']}`
        if ( confirm("Are you sure?") ) {
            $('#inventoryWindow').jqxWindow('close')
            
            $.post('../api.admin.addItem/',
                {
                    userId: _userId,
                    name: form_val['name'],
                    asset: form_val['asset'],
                    rarity: form_val['rarity'],
                    price: form_val['price']
                },
                function (data, status) {
                    if (data.status == 'success')
                        $('#inventory').append(htmlInventory(
                            {...data, name: form_val['name'], asset: form_val['asset'], rarity: form_val['rarity']}
                        ))
                }
            )
        }
    })

    $('#inventoryWindow').jqxWindow('open')
}

function removeInventory( id ) {
    const url = "../api.admin.removeItem/" + id
    if ( confirm("Are you sure?") ) 
        $.getJSON(url, function(data) {
            if (data.status == 'success') $('#inventory_' + id).remove()
        })
}

function htmlEvent( data ) {
    let eventDatetime = new Date(data.start_timestamp)
    let currentDatetime = Date.now()
    let state = ''

    if (eventDatetime > currentDatetime) 
        state = 'Hosted'
    else 
        state = 'Starts in'

    return `<div class="project-box-wrapper" id="event_${data.id}">
                <div class="project-box random-blue">
                    <div class="project-box-header">
                        <span>${moment(eventDatetime).format("MMM Do YY | h:mm a")}</span>
                        <a href="#" onclick="removeEvent(${data.id})">Remove</a>
                    </div>
                    <div class="project-box-content-header">
                        <p class="box-content-header">${data.game}</p>
                        <p class="box-content-subheader">${data.platform}</p>
                    </div>
                    <div class="project-box-footer">
                        <div class="participants" title="Host : ${data.host.username}">
                            <img src="${data.host.avatar}" alt="participant">
                        </div>
                        <div class="badge-storage">
                            <div class="badge-trans">
                                ${state} ${moment(eventDatetime).fromNow()}
                            </div>
                        </div>
                    </div>
                </div>
            </div>`
}

function addEvent() {
    let template = [
        {
            bind: 'platform',
            type: 'text',
            label: 'Platform',
            labelPosition: 'left',
            labelWidth: '30%',
            align: 'left',
            width: '250px',
            required: true
        },
        {
            bind: 'game',
            type: 'text',
            label: 'Game',
            labelPosition: 'left',
            labelWidth: '30%',
            align: 'left',
            width: '250px',
            required: false
        },
        {
            bind: 'start_time',
            type: 'text',
            label: 'Start Time',
            labelPosition: 'left',
            labelWidth: '30%',
            align: 'left',
            width: '250px',
            required: true
        },
        {
            name: 'btnAddEvent',
            type: 'button',
            text: 'Add Event',
            align: 'right',
            padding: {left: 0, top: 5, bottom: 5, right: 40}
        }
    ]

    let eventForm = $('#eventForm')

    eventForm.jqxForm({
        template: template,
        value: [],
        padding: { left: 10, top: 10, right: 0, bottom: 10 }
    })

    let btn = eventForm.jqxForm('getComponentByName', 'btnAddEvent')
    btn.on('click', function (event) {
        let form_val = eventForm.val()

        if ( form_val['name'] == "" ) return
        
        const url = `../api.admin.addEvent/${_userId}&${form_val['platform']}&${form_val['game']}&${form_val['start_time']}`
        if ( confirm("Are you sure?") ) {
            $('#eventWindow').jqxWindow('close')

            $.getJSON(url, function(data) {
                if (data.status == 'success') 
                    $('#events').append(htmlEvent({
                        ...data,
                        game:               form_val['game'],
                        platform:           form_val['platform'],
                        start_timestamp:    form_val['start_time'],
                        host: {
                            username:       _userName,
                            avatar:         _userAvatar
                        }
                    }))
            })
        }
    })

    $('#eventWindow').jqxWindow('open')
}

function removeEvent(id) {
    const url = "../api.admin.removeEvent/" + id
    if ( confirm("Are you sure?") )
        $.getJSON(url, function(data) {
            if (data.status == 'success') $('#event_' + id).remove()
        })
}

function getUserProfile() {
    const url = "../api.user.info/" + _userId
    $.getJSON(url, function(data) {
        _userAvatar = data.avatar
        _userName = data.username

        if (data.status == 'success') {
            $('#page-title.projects-section-header p').html(String(data.username))

            $('#gems-wrapper #gems').html('<span title="' + String(data.gems) + '">' + String(nFormatter(data.gems, 1)) + '</span>')
            
            // if (data.online === 1) {
            //     $('#status-wrapper #status').html('<span style="color: rgb(111, 192, 111);">Online</span>')
            // } else if (data.online === 2) {
            //     $('#status-wrapper #status').html('<span style="color: rgb(191, 192, 111);">Idle</span>')
            // } else {
            //     $('#status-wrapper #status').html('<span style="color: rgb(192, 111, 111);">Offline</span>')
            // }

            $('#badges table').empty()
            $('#inventory').empty()
            $('#posts').empty()
            $('#events').empty()

            $.each(data.badges, function(key, val) {
                $('#badges table').append(htmlBadge(val))
            })

            // if (data.badges.length > 3) {
            //     $('#badges').append(
            //         '<span class="more-badge" style="display: none;">+</span>'
            //     )
            // }

            $.each(data.inventory, function(key, val) {
                $('#inventory').append(htmlInventory(val))
            });

            $.each(data.events, function(key, val) {
                $('#events').append(htmlEvent(val))
            })
            
            /// TODO: posts
        } else {
            $('#error-wrapper').show()
        }
    })
}

document.addEventListener('DOMContentLoaded', function() {
    $('#badgeWindow').jqxWindow({
        position: { x: 100, y: 50} ,
        showCollapseButton: true, maxHeight: 400, maxWidth: 700, minHeight: 145, minWidth: 200, height: 145, width: 400,
        autoOpen: false,
    })

    $('#inventoryWindow').jqxWindow({
        position: { x: 100, y: 50} ,
        showCollapseButton: true, maxHeight: 400, maxWidth: 700, minHeight: 265, minWidth: 200, height: 265, width: 400,
        autoOpen: false,
    })

    $('#eventWindow').jqxWindow({
        position: { x: 100, y: 50} ,
        showCollapseButton: true, maxHeight: 400, maxWidth: 700, minHeight: 265, minWidth: 200, height: 265, width: 400,
        autoOpen: false,
    })

    getUserProfile()
})
