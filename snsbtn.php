<div class="share"><ul>
    <li><a href="https://twitter.com/intent/tweet?text=<?php echo urlencode( get_the_title() . ' - ' . get_bloginfo('name') ); ?>&amp;url=<?php echo urlencode( get_permalink() ); ?>&amp;via=sagaswhat"
    onclick="window.open(this.href, 'SNS', 'width=500, height=300, menubar=no, toolbar=no, scrollbars=yes'); return false;" class="share-tw">
        <i class="fa fa-twitter"></i>
        <span>share&nbsp;by</span>&nbsp;Twitter
    </a></li>
    <li><a href="http://www.facebook.com/share.php?u=<?php echo urlencode( get_permalink() ); ?>"
    onclick="window.open(this.href, 'SNS', 'width=500, height=500, menubar=no, toolbar=no, scrollbars=yes'); return false;" class="share-fb">
        <i class="fa fa-facebook"></i>
        <span>share&nbsp;by</span>&nbsp;Facebook
    </a></li>
    <li><a href="https://plus.google.com/share?url=<?php echo urlencode( get_permalink() ); ?>"
    onclick="window.open(this.href, 'SNS', 'width=500, height=500, menubar=no, toolbar=no, scrollbars=yes'); return false;" class="share-gp">
        <i class="fa fa-google-plus"></i>
        <span>share&nbsp;by</span>&nbsp;Google+
    </a></li>
    </ul>
</div><!-- end share -->
