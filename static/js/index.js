var _searchKey = ''

function setCookie(cname, cvalue, exdays) {
    const d = new Date()
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000))
    let expires = "expires=" + d.toUTCString()
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/"
}

function getCookie(cname) {
    let name = cname + "="
    let ca = document.cookie.split(';')
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i]
        while (c.charAt(0) == ' ') {
            c = c.substring(1)
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length)
        }
    }
    return ""
}

function colorRandomBlue() {
    var cusid_ele = document.getElementsByClassName('random-blue')
    var colors = [
        '#4250ee', '#3b48d6', '#3540be',
        '#4c56cd', '#323cb3',
    ]
    for (var i = 0; i < cusid_ele.length; ++i) {
        var item = cusid_ele[i]
        var random_color = colors[Math.floor(Math.random() * colors.length)]
        item.style.background = random_color
    }
}

function dropdown() {
    var x = document.getElementById("Demo")
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show"
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}


function htmlSearchResult( data, search ) {
    let context = '', temp = data.user_name

    while (search != '') {
        let index = temp.search(new RegExp(search, 'ig'))
        if (index == -1) break

        context += temp.slice(0, index)
        context += '<span class="search-matched">'
        context += temp.slice(index, index + search.length)
        context += '</span>'

        temp = temp.slice(index + search.length, temp.length)
    }
    context += temp

    return `<a href="/user/${data.user_id}" style="display: inline-flex !important; width:100%">
                <img src="${data.user_avatar}">
                <span style="margin-left: 20px">${context}</span>
            </a>`
}

function searchUser() {
    $('.search-dropdown-content').html(
        `<a href="#" style="display: inline-flex !important; width:100%">
            <div class="loader"></div>
        </a>`
    )
    
    $.post('../api.search.user/',
        {
            search: _searchKey
        },
        function (data, status) {
            if (status == 'success' && data.status == 'success' && data.search == _searchKey) {
                $('.search-dropdown-content').empty()
                
                $.each(data.users, function(key, val) {
                    $('.search-dropdown-content').append(htmlSearchResult(val, _searchKey))
                })
            }
        }
    )
}

document.addEventListener('DOMContentLoaded', function() {

    setInterval(function() {
        $(".page-loader").hide()
        $(".app-container").show()
    }, 0)

    colorRandomBlue()

    var modeSwitch = document.querySelector('.mode-switch')

    if (getCookie('display-mode') == 'light') {
        document.documentElement.classList.add('light')
        modeSwitch.innerHTML = "<i data-feather='moon'></i>"
    } else {
        document.documentElement.classList.remove('light')
        modeSwitch.innerHTML = "<i data-feather='sun'></i>"
    }

    feather.replace()

    modeSwitch.addEventListener('click', function() {
        if (getCookie('display-mode') == 'light') {
            setCookie('display-mode', 'dark', 365)
            document.documentElement.classList.remove('light')
            modeSwitch.innerHTML = "<i data-feather='sun'></i>"
        } else {
            setCookie('display-mode', 'light', 365)
            document.documentElement.classList.add('light')
            modeSwitch.innerHTML = "<i data-feather='moon'></i>"
        }
        feather.replace()
    })

    $('.search-input').keyup(function() {
        if ($('.search-input').val() != _searchKey) {
            _searchKey = $('.search-input').val()
    
            searchUser()
        }
    })

    $('.search-input').click(function() {
        if (! $('#search-dropdown').hasClass('search-dropdown') )
            $('#search-dropdown').addClass('search-dropdown');
    })


    $('.projects-section').click(function() {
        if ( $('#search-dropdown').hasClass('search-dropdown') )
            $('#search-dropdown').removeClass('search-dropdown');
    })
    
    searchUser()
})
