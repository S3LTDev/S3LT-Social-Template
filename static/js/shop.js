var _pageIndex = 0
    _pageSize = 3 * 5,
    _rarity = 0,
    _itemName = '';

function buyShopItem(itemId) {
    if ( confirm("Are you sure?") ) {
        $.post("../api.shop.buy", 
            {
                itemId: itemId,
                userId: userId
            },
            function(data, status) {
                if (data.status == 'success' && status == 'success') {
                    infoSnackBar('Item is added to your inventory.')
                    updateGems()
                } else {
                    errorSnackBar(data.message)
                }
            }
        )
    }
}

function updateShop() {
    $.post("../api.shop.items", 
        {
            pageIndex: _pageIndex,
            pageSize: _pageSize,
            rarity: _rarity,
            itemName: _itemName
        }, 
        function(data, status) {

            if (data.status == 'success' && status == 'success') {

                $('.project-boxes').empty()
                $('#all-items').html(String(data.items.length))

                $.each(data.items, function(key, val) {
                    $('.project-boxes').append(
                        `<div class="project-box-wrapper">
                            <div class="project-box random-blue">
                                <div class="project-box-header">
                                    <span>${val.name}</span>
                                </div>
                                <div class="project-box-content-header">
                                    <img class="inventory-img" src="${val.asset}" alt="">
                                </div>
                                <div class="project-box-footer">
                                    <div class="box-progress-wrapper">
                                        <p class="box-progress-header">${val.rarity}</p>
                                    </div>
                                    <div class="badge-storage">
                                        <div class="badge-trans">
                                            Price ${nFormatter(val.price, 1)}💎
                                        </div>
                                        <div class="badge-trans">
                                            <a onclick="buyShopItem(${val.id})">Purchase</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`
                    )
                })

                colorRandomBlue()
            } else {

            }
        }
    )
}

function updateGems() {
    const url = "../api.user.info/" + userId
    $.getJSON(url, function(data) {

        if (data.status == 'success') {
            $('#user-gems').html(String(nFormatter(data.gems, 1)));
        } else {
            $('#error-wrapper').show();
        }
    }).done(function() {
        colorRandomBlue()
    })
}

function updateRarity() {
    $.getJSON('../api.rarity.list', function(data) {
        if (data.status == 'success') {
            $('#rarity-select').empty()

            $.each(data.rarity, function(key, val) {
                $('#rarity-select').append(
                    `<option value="${val.id}" ${key == 0 ? 'selected' : ''}>${val.rarity}</option>`
                )
            })
            _rarity = data.rarity[0].id
        } else {

        }
    }).done(function() {

    })
}

document.addEventListener('DOMContentLoaded', function() {

    updateGems()
    updateShop()
    updateRarity()

    setInterval(function() {
        updateShop()
        updateGems()
    }, 30000)

    $('#rarity-select').on('change', function() {
        _rarity = this.value
        updateShop()
    })

    $('#name-select').on('change', function() {
        _itemName = this.value
        updateShop()
    })
});