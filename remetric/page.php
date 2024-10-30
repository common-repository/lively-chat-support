<style type="text/css">
    /* WP OVERRIDES */

    @media screen and (min-width: 782px) {
        .toplevel_page_remetric #wpbody {
            margin-left: -20px;
        }
    }

    @media screen and (max-width: 781px) {
        .toplevel_page_remetric #wpbody {
            margin-left: -10px;
        }

        .toplevel_page_remetric .wrap {
            position: relative;
            margin: 0;
        }
    }

    .toplevel_page_remetric .wrap {
        margin: 0;
    }

    .update-nag {
        display: none;
    }

    .wp-core-ui .remetric-admin .button-secondary:focus, .wp-core-ui .remetric-admin .button-secondary:hover, .wp-core-ui .remetric-admin .button.focus, .wp-core-ui .remetric-admin .button.hover, .wp-core-ui .remetric-admin .button:focus, .wp-core-ui .remetric-admin .button:hover {
        color: #fff;
    }

    #remetric-admin .remetric-admin .loading {
        padding: 100px 20px;
        font-family: Helvetica Neue, Helvetica, Arial, Sans Serif;
        font-size: 16px;
        line-height: 1;
        font-weight: 300;
        text-align: center;
        margin: 0;
    }
</style>

<div class="wrap">
    <div id="remetric-admin">
        <div class="remetric-admin">
            <p class="loading">Loading Remetric...</p>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.remetricTarget = document.getElementById('remetric-admin');
    window.remetricEvents = function(event, user, done) {
        jQuery.post(window.ajaxurl, {
            action: 'remetric_save_keys',
            remetric_access_token: event === 'logout' ? '' : btoa(user.api_token + ':' + user.secret_key),
            remetric_publishable_key: event === 'logout' ? '' : user.account.token,
        }, done);
    };

    (function(m, a, r, k, e, t, i, n, g) {
        // Remetric v<?php echo $remetric->version; ?>

        var b = document.createElement('script');
        b.type = 'text/javascript';
        b.async = true;
        b.src = m;
        b.setAttribute('data-remetric-admin', true);
        if (a) b.setAttribute('data-publishable-key', a);
        if (r) b.setAttribute('data-access-token', r);
        if (k) b.setAttribute('data-api-url', k);
        if (e) b.setAttribute('data-cdn-url', e);
        if (t) b.setAttribute('data-css-url', t);
        if (i) b.setAttribute('data-target', i);
        if (n) b.setAttribute('data-whitelabel', n);
        g = document.getElementsByTagName('script')[0];
        g.parentNode.insertBefore(b, g);
    })(
        '<?php echo $remetric->admin_js; ?>',
        '<?php echo $remetric->publishable_key; ?>',
        '<?php echo $remetric->access_token; ?>',
        '<?php echo $remetric->api_url; ?>',
        '<?php echo $remetric->cdn_url; ?>',
        '<?php echo $remetric->admin_css; ?>',
        '<?php echo $remetric->target; ?>',
        '<?php echo $remetric->whitelabel; ?>'
    );
</script>
