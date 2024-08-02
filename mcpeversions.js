$(document).ready(function () {
    let versions = [];

    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        const results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    const initialType = getUrlParameter('type') || 'All';

    $.getJSON('versions.json', function (data) {
        versions = data;
        displayVersions(initialType);
    });

    function displayVersions(type) {
        $('#version-list').empty();
        const filteredVersions = versions.filter(version => {
            const versionTypes = version.type.split(',').map(type => type.trim());
            return type === 'All' || versionTypes.includes(type);
        });

        filteredVersions.forEach(version => {
            $('#version-list').append(`
                <div class="version-item">
                    <h4>${version.version}</h4>
                    <a href="${version.download_link}" target="_blank" class="btn btn-primary">Download</a>
                </div>
            `);
        });

        $('.tab').removeClass('active');
        $(`.tab[data-type="${type}"]`).addClass('active');
    }

    $('.tab').on('click', function () {
        const type = $(this).data('type');
        displayVersions(type);

        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?type=' + type;
        window.history.pushState({ path: newUrl }, '', newUrl);
    });

    $('#search-input').on('keyup', function () {
        const query = $(this).val().toLowerCase();
        $('#version-list').empty();
        const filteredVersions = versions.filter(version => version.version.toLowerCase().includes(query));
        filteredVersions.forEach(version => {
            $('#version-list').append(`
                <div class="version-item">
                    <h4>${version.version}</h4>
                    <a href="${version.download_link}" target="_blank" class="btn btn-primary">Download</a>
                </div>
            `);
        });
    });
});
