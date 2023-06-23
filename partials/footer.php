<footer>
    <div class="footer_socials">
        <a href="https://youtube.com" target="_blank"><i class="uil uil-youtube"></i></i></a>
        <a href="https://facebook.com" target="_blank"><i class="uil uil-facebook"></i></i></a>
        <a href="https://instagram.com" target="_blank"><i class="uil uil-instagram"></i></i></a>
        <a href="https://twitter.com" target="_blank"><i class="uil uil-twitter"></i></i></a>
    </div>
    <div class="container footer_container">
        <article>
            <h4>Categories</h4>
            <ul>
                <?php
                $all_categories_query = "SELECT * FROM categories LIMIT 5";
                $all_categories = mysqli_query($connection, $all_categories_query);
                ?>
                 <?php while ($category = mysqli_fetch_assoc($all_categories)) : ?>
                <li><a href="<?=ROOT_URL?>category-post.php?id=<?= $category['id'] ?>"><?=$category['title']?></a></li>
                <?php endwhile ?>
        </article>

        <article>
            <h4>Blog</h4>
            <ul>
                <li><a href="">Popular</a></li>
                <li><a href="">Categories</a></li>
            </ul>
        </article>

        <article>
            <h4>Permalinks</h4>
            <ul>
                <li><a href="">Home</a></li>
                <li><a href="">Blog</a></li>
                <li><a href="">Calculator</a></li>
                <li><a href="">Contact Us</a></li>
            </ul>
        </article>


        <article>
            <h4>Support</h4>
            <ul>
                <li><a href="<?= ROOT_URL ?>contact.php">Online Support</a></li>
            </ul>
        </article>

    </div>
    <div class="footer_copyright">
        <small>Copyright &copy;2023 FinartZ</small>
    </div>
</footer>

<!-- ========== END OF FOOTER ========== -->

<!-- LINKING JS -->
<script src="<?= ROOT_URL ?>js/main.js"></script>
</body>

</html>