<?php

class TlpPortfolioElementorWidget extends \Elementor\Widget_Base
{

    public function get_name() {
        return 'tlp-portfolio';
    }

    public function get_title() {
        return __('Tlp Portfolio', 'tlp-portfolio');
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        global $TLPportfolio;
        $this->start_controls_section(
            'setting_section',
            [
                'label' => __('Settings', 'tlp-portfolio'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout',
            array(
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'id'      => 'layout',
                'label'   => __('Layout', 'tlp-portfolio'),
                'options' => $TLPportfolio->oldScLayouts()
            )
        );
        $this->add_control(
            'col',
            array(
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'id'      => 'col',
                'label'   => __('Column', 'tlp-portfolio'),
                'options' => $TLPportfolio->scColumns()
            )
        );
        $this->add_control(
            'orderby',
            array(
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'id'      => 'orderby',
                'label'   => __('Order By', 'tlp-portfolio'),
                'options' => $TLPportfolio->scOrderBy()
            )
        );
        $this->add_control(
            'order',
            array(
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'id'      => 'order',
                'label'   => __('Order', 'tlp-portfolio'),
                'options' => $TLPportfolio->scOrder()
            )
        );
        $this->add_control(
            'number',
            array(
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'id'      => 'number',
                'label'   => __('Total Number', 'tlp-portfolio'),
                'step'    => 1,
                'default' => '',
            )
        );
        $this->add_control(
            'cat',
            array(
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'id'       => 'cat',
                'label'    => __('Category', 'tlp-portfolio'),
                'options'  => $TLPportfolio->getAllPortFolioCategoryList(),
                'multiple' => true
            )
        );
        $this->add_control(
            'image',
            array(
                'label'        => __('Hide Image', 'tlp-portfolio'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __('Hide', 'tlp-portfolio'),
                'label_off'    => __('Show', 'tlp-portfolio'),
                'return_value' => 'false'
            )
        );
        $this->add_control(
            'letter-limit',
            array(
                'label'       => __('Short description limit', 'tlp-portfolio'),
                'description' => __("Leave it blank to default 100 letter", 'tlp-portfolio'),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'step'        => 1,
                'default'     => '',
            )
        );
        $this->add_control(
            'class',
            array(
                'label'       => __('Wrapper Class', 'tlp-portfolio'),
                'type'        => \Elementor\Controls_Manager::TEXT,
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'title_style_section',
            [
                'label' => __('Title Style', 'tlp-portfolio'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title-color',
            array(
                'label'     => __('Color', 'tlp-portfolio'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'scheme'    => array(
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'title-font-size',
            array(
                'label'   => __('Font size', 'tlp-portfolio'),
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'options' => $TLPportfolio->scFontSize()
            )
        );
        $this->add_control(
            'title-font-weight',
            array(
                'label'   => __('Font weight', 'tlp-portfolio'),
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'options' => $TLPportfolio->scTextWeight()
            )
        );
        $this->add_control(
            'title-alignment',
            array(
                'label'   => __('Alignment', 'tlp-portfolio'),
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'options' => $TLPportfolio->scAlignment()
            )
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'short_description_style_section',
            [
                'label' => __('Short Description Style', 'tlp-portfolio'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'short-desc-color',
            array(
                'label'     => __('Color', 'tlp-portfolio'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'scheme'    => array(
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'short-desc-font-size',
            array(
                'label'   => __('Font size', 'tlp-portfolio'),
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'options' => $TLPportfolio->scFontSize()
            )
        );
        $this->add_control(
            'short-desc-font-weight',
            array(
                'label'   => __('Font weight', 'tlp-portfolio'),
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'options' => $TLPportfolio->scTextWeight()
            )
        );
        $this->add_control(
            'short-desc-alignment',
            array(
                'label'   => __('Alignment', 'tlp-portfolio'),
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'options' => $TLPportfolio->scAlignment()
            )
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $shortcode = '[tlpportfolio';
        if (isset($settings['layout']) && !empty($settings['layout'])) {
            $shortcode .= ' layout="' . $settings['layout'] . '"';
        }
        if (isset($settings['col']) && !empty($settings['col'])) {
            $shortcode .= ' col="' . $settings['col'] . '"';
        }
        if (isset($settings['orderby']) && !empty($settings['orderby'])) {
            $shortcode .= ' orderby="' . $settings['orderby'] . '"';
        }
        if (isset($settings['order']) && !empty($settings['order'])) {
            $shortcode .= ' order="' . $settings['order'] . '"';
        }
        if (isset($settings['number']) && !empty($settings['number'])) {
            $shortcode .= ' number="' . $settings['number'] . '"';
        }
        if (isset($settings['cat']) && !empty($settings['cat']) && is_array($settings['cat'])) {
            $shortcode .= ' cat="' . implode(',', $settings['cat']) . '"';
        }
        if (isset($settings['image']) && !empty($settings['image'])) {
            $shortcode .= ' image="false"';
        }
        if (isset($settings['letter-limit']) && !empty($settings['letter-limit'])) {
            $shortcode .= ' letter-limit="' . $settings['letter-limit'] . '"';
        }
        if (isset($settings['title-color']) && !empty($settings['title-color'])) {
            $shortcode .= ' title-color="' . $settings['title-color'] . '"';
        }
        if (isset($settings['title-font-size']) && !empty($settings['title-font-size'])) {
            $shortcode .= ' title-font-size="' . $settings['title-font-size'] . '"';
        }
        if (isset($settings['title-font-weight']) && !empty($settings['title-font-weight'])) {
            $shortcode .= ' title-font-weight="' . $settings['title-font-weight'] . '"';
        }
        if (isset($settings['title-alignment']) && !empty($settings['title-alignment'])) {
            $shortcode .= ' title-alignment="' . $settings['title-alignment'] . '"';
        }
        if (isset($settings['short-desc-color']) && !empty($settings['short-desc-color'])) {
            $shortcode .= ' short-desc-color="' . $settings['short-desc-color'] . '"';
        }
        if (isset($settings['short-desc-font-size']) && !empty($settings['short-desc-font-size'])) {
            $shortcode .= ' short-desc-font-size="' . $settings['short-desc-font-size'] . '"';
        }
        if (isset($settings['short-desc-font-weight']) && !empty($settings['short-desc-font-weight'])) {
            $shortcode .= ' short-desc-font-weight="' . $settings['short-desc-font-weight'] . '"';
        }
        if (isset($settings['short-desc-alignment']) && !empty($settings['short-desc-alignment'])) {
            $shortcode .= ' short-desc-alignment="' . $settings['short-desc-alignment'] . '"';
        }
        if (isset($settings['class']) && !empty($settings['class'])) {
            $shortcode .= ' class="' . $settings['class'] . '"';
        }
        $shortcode .= ']';

        echo do_shortcode($shortcode);
    }
}