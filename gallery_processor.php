<?php

class GalleryProcessor {
    
    protected static $gallery_component_id = 1; // ID компонента "Галереи"
    protected static $image_field_id = 1; // ID поля с изображениями в галерее

    /**
     * Обработка контента статьи - замена {{gallery_№}} на HTML галереи
     */
    public static function processContent($content) {
        // Ищем все вхождения {{gallery_№}}
        preg_match_all('/\{\{gallery_(\d+)\}\}/', $content, $matches);
        
        if (empty($matches[1])) {
            return $content;
        }

        $gallery_ids = array_unique($matches[1]);
        
        foreach ($gallery_ids as $gallery_id) {
            $gallery_html = self::renderGallery($gallery_id);
            // Заменяем все вхождения этого ID галереи
            $content = str_replace("{{gallery_{$gallery_id}}}", $gallery_html, $content);
        }

        return $content;
    }

    /**
     * Рендер галереи по ID объекта
     */
    public static function renderGallery($gallery_id) {
        global $nc_core;
        
        $db = $nc_core->db;
        
        // Получаем данные объекта галереи
        $gallery_data = $db->get_row("
            SELECT Message_ID, Name, Description
            FROM Message" . self::$gallery_component_id . " 
            WHERE Message_ID = " . (int)$gallery_id . "
            AND Checked = 1
        ", ARRAY_A);

        if (!$gallery_data) {
            return '<div class="gallery-error">Галерея #' . $gallery_id . ' не найдена</div>';
        }

        // Получаем изображения галереи
        $images = $db->get_results("
            SELECT File_Path, Description, File_Type
            FROM Multifield 
            WHERE Message_ID = " . (int)$gallery_id . "
            AND Field_ID = " . self::$image_field_id . "
            AND File_Type IN ('jpeg', 'jpg', 'png', 'gif', 'webp')
            ORDER BY Priority
        ", ARRAY_A);

        if (empty($images)) {
            return '<div class="gallery-error">В галерее #' . $gallery_id . ' нет изображений</div>';
        }

        // Генерируем HTML галереи
        return self::generateGalleryHTML($gallery_data, $images);
    }

    /**
     * Генерация HTML карусели
     */
    private static function generateGalleryHTML($gallery_data, $images) {
        $gallery_title = htmlspecialchars($gallery_data['Name'] ?? '');
        $gallery_description = htmlspecialchars($gallery_data['Description'] ?? '');
        $gallery_id = 'gallery-' . $gallery_data['Message_ID'];
        
        ob_start();
        ?>
        <div class="article-gallery" id="<?= $gallery_id ?>">
            <?php if ($gallery_title): ?>
            <div class="gallery-header">
                <h4 class="gallery-title"><?= $gallery_title ?></h4>
                <?php if ($gallery_description): ?>
                <p class="gallery-description"><?= $gallery_description ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <div class="article-carousel">
                <div class="carousel-container">
                    <?php foreach ($images as $index => $image): ?>
                    <div class="carousel-slide" data-slide-index="<?= $index ?>">
                        <img src="<?= $image['File_Path'] ?>" 
                             alt="<?= htmlspecialchars($image['Description'] ?? '') ?>" 
                             class="carousel-image" 
                             loading="lazy" />
                        <?php if (!empty($image['Description'])): ?>
                        <div class="carousel-caption"><?= htmlspecialchars($image['Description']) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (count($images) > 1): ?>
                <button class="carousel-prev" aria-label="Предыдущее изображение">❮</button>
                <button class="carousel-next" aria-label="Следующее изображение">❯</button>
                <div class="carousel-dots">
                    <?php for ($i = 0; $i < count($images); $i++): ?>
                    <span class="dot<?= $i === 0 ? ' active' : '' ?>" data-slide="<?= $i ?>"></span>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Установка ID компонента галереи
     */
    public static function setGalleryComponentId($id) {
        self::$gallery_component_id = (int)$id;
    }

    /**
     * Установка ID поля с изображениями
     */
    public static function setImageFieldId($id) {
        self::$image_field_id = (int)$id;
    }
}

?>