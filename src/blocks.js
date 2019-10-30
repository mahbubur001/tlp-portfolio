//  Import CSS.
import './scss/style.scss';
import './scss/editor.scss';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;
const {SelectControl, PanelBody, TextControl, CheckboxControl} = wp.components;
const {InspectorControls, ColorPalette} = wp.editor;
registerBlockType('rt-portfolio/tlp-portfolio-pro', {
    title: __('TLP Portfolio', "tlp-portfolio"),
    icon: 'grid-view',
    category: 'common',
    description: __('This is the tlp portfolio settings section', "the-portfolio"),
    keywords: [
        __('Tlp Portfolio Pro', "tlp-portfolio"),
        __('tlp-portfolio', "tlp-portfolio"),
    ],
    attributes: {
        gridId: {
            type: 'number',
            default: 0,
        }
    },
    edit: function (props) {
        let {attributes: {gridId}, setAttributes} = props;
        let gridTitle = "";
        let options = [{value: 0, label: __("Select one", "tlp-portfolio")}];
        if (rtPortfolio.short_codes) {
            for (const [id, title] of Object.entries(rtPortfolio.short_codes)) {
                options.push({
                    value: id,
                    label: title
                });
                if (gridId && Number(id) === gridId) {
                    gridTitle = title;
                }
            }
        }
        return (
            [
                <InspectorControls>
                    <PanelBody title={__('ShortCode', 'tlp-portfolio')} initialOpen={true}>
                        <SelectControl
                            label={__('Select a grid:', "tlp-portfolio")}
                            options={options}
                            value={gridId}
                            onChange={(val) => setAttributes({gridId: Number(val)})}
                        />
                    </PanelBody>
                </InspectorControls>
                ,
                <div className={props.className}>
                    {!gridId ? (<p>{__("Please select a shortcode from block settings", "tlp-portfolio")}</p>) : (
                        <div><span><img style={{verticalAlign: 'middle'}} src={rtPortfolio.icon}/></span> <span>{__('TLP Portfolio', "tlp-portfolio")} ( {gridTitle} )</span></div>
                    )}
                </div>
            ]
        );
    },

    save: function () {
        return null;
    },
});

registerBlockType('radiustheme/tlp-portfolio', {
    title: __('TLP Portfolio (Deprecated)', "tlp-portfolio"),
    icon: 'grid-view',
    description: __('This shortcode is deprecated and this will remove end of this year (2019). Please use our new Block (TLP Portfolio)', "tlp-portfolio"),
    category: 'common',
    keywords: [
        __('Tlp Portfolio', "tlp-portfolio"),
        __('tlp-portfolio', "tlp-portfolio"),
    ],
    attributes: {
        layout: {
            type: 'number',
            default: 1,
        },
        column: {
            type: 'number',
            default: 4,
        },
        orderby: {
            type: 'string',
            default: null,
        },
        order: {
            type: 'string',
            default: null,
        },
        number: {
            type: 'number',
            default: 0,
        },
        cats: {
            type: 'array',
            default: [],
        },
        isImageHide: {
            type: 'boolean',
            default: false,
        },
        letterLimit: {
            type: 'number',
            default: null,
        },
        wrapperClass: {
            type: 'string',
            default: null,
        },
        titleColor: {
            type: 'string',
            default: null
        },
        titleFontWeight: {
            type: 'string',
            default: null
        },
        titleFontSize: {
            type: 'string',
            default: null
        },
        titleAlignment: {
            type: 'string',
            default: null
        },
        sdColor: {
            type: 'string',
            default: null
        },
        sdFontWeight: {
            type: 'string',
            default: null
        },
        sdFontSize: {
            type: 'string',
            default: null
        },
        sdAlignment: {
            type: 'string',
            default: null
        },
    },
    edit: function (props) {
        let {attributes: {layout, column, orderby, order, titleColor, titleFontWeight, titleFontSize, titleAlignment, sdFontWeight, sdFontSize, sdAlignment, sdColor, cats, number, wrapperClass, isImageHide, letterLimit}, setAttributes} = props;
        let layouts = [{value: 0, label: __("Default", "tlp-portfolio")}];
        let columns = [{value: 0, label: __("Default", "tlp-portfolio")}];
        let orderByS = [{value: null, label: __("Default", "tlp-portfolio")}];
        let orders = [{value: null, label: __("Default", "tlp-portfolio")}];
        let alignments = [{value: null, label: __("Default", "tlp-portfolio")}];
        let fontWeights = [{value: null, label: __("Default", "tlp-portfolio")}];
        let fontSizes = [{value: null, label: __("Default", "tlp-portfolio")}];
        let catList = [{value: 0, label: __("All", "tlp-portfolio")}];
        if (rtPortfolio.layout) {
            for (const [id, title] of Object.entries(rtPortfolio.layout)) {
                layouts.push({
                    value: id,
                    label: title
                });
            }
        }
        if (rtPortfolio.column) {
            for (const [id, title] of Object.entries(rtPortfolio.column)) {
                columns.push({
                    value: id,
                    label: title
                });
            }
        }
        if (rtPortfolio.orderby) {
            for (const [id, title] of Object.entries(rtPortfolio.orderby)) {
                orderByS.push({
                    value: id,
                    label: title
                });
            }
        }
        if (rtPortfolio.order) {
            for (const [id, title] of Object.entries(rtPortfolio.order)) {
                orders.push({
                    value: id,
                    label: title
                });
            }
        }
        if (rtPortfolio.cats) {
            for (const [id, title] of Object.entries(rtPortfolio.cats)) {
                catList.push({
                    value: id,
                    label: title
                });
            }
        }
        if (rtPortfolio.alignments) {
            for (const [id, title] of Object.entries(rtPortfolio.alignments)) {
                alignments.push({
                    value: id,
                    label: title
                });
            }
        }
        if (rtPortfolio.fontWeights) {
            for (const [id, title] of Object.entries(rtPortfolio.fontWeights)) {
                fontWeights.push({
                    value: id,
                    label: title
                });
            }
        }
        if (rtPortfolio.fontSizes) {
            for (const [id, title] of Object.entries(rtPortfolio.fontSizes)) {
                fontSizes.push({
                    value: id,
                    label: title
                });
            }
        }
        return (
            [
                <InspectorControls>
                    <PanelBody title={__('Layout & Filter Settings', 'tlp-portfolio')} initialOpen={true}>
                        <SelectControl
                            label={__('Layout:', "tlp-portfolio")}
                            options={layouts}
                            value={layout}
                            onChange={(val) => setAttributes({layout: Number(val)})}
                        />
                        <SelectControl
                            label={__('Column / Number to Display at slider:', "tlp-portfolio")}
                            options={columns}
                            value={column}
                            onChange={(val) => setAttributes({column: Number(val)})}
                        />
                        <SelectControl
                            label={__('Order By:', "tlp-portfolio")}
                            options={orderByS}
                            value={orderby}
                            onChange={(val) => setAttributes({orderby: val})}
                        />
                        <SelectControl
                            label={__('Order:', "tlp-portfolio")}
                            options={orders}
                            value={order}
                            onChange={(val) => setAttributes({order: val})}
                        />
                        <TextControl
                            label={__('Short description limit', 'tlp-portfolio')}
                            help={__("Leave it blank to default 100 letter", 'tlp-portfolio')}
                            value={letterLimit ? letterLimit : null}
                            onChange={(val) => {
                                let letterLimit = Number(val) ? Number(val) : '';
                                setAttributes({letterLimit})
                            }}
                        />
                        <CheckboxControl
                            label={__('Hide Image', 'tlp-portfolio')}
                            checked={isImageHide}
                            onChange={(isImageHide) => {
                                setAttributes({isImageHide})
                            }}
                        />
                        <TextControl
                            label={__('Total Number', 'tlp-portfolio')}
                            value={number ? number : null}
                            onChange={(val) => setAttributes({number: Number(val)})}
                        />
                        <SelectControl
                            label={__('Category:', "tlp-portfolio")}
                            options={catList}
                            value={cats}
                            multiple={true}
                            onChange={(val) => setAttributes({cats: val})}
                        />

                    </PanelBody>
                    <PanelBody title={__('Style Settings', 'tlp-portfolio')} initialOpen={false}>
                        <label>{__('Title Color:', "tlp-portfolio")}</label>
                        <ColorPalette
                            value={titleColor}
                            onChange={(val) => setAttributes({titleColor: val})}
                        />
                        <SelectControl
                            label={__('Title Font size:', "tlp-portfolio")}
                            options={fontSizes}
                            value={titleFontSize}
                            onChange={(val) => setAttributes({titleFontSize: val})}
                        />
                        <SelectControl
                            label={__('Title Font weight:', "tlp-portfolio")}
                            options={fontWeights}
                            value={titleFontWeight}
                            onChange={(val) => setAttributes({titleFontWeight: val})}
                        />
                        <SelectControl
                            label={__('Title Alignment:', "tlp-portfolio")}
                            options={alignments}
                            value={titleAlignment}
                            onChange={(val) => setAttributes({titleAlignment: val})}
                        />
                        <label>{__('Short desc color:', "tlp-portfolio")}</label>
                        <ColorPalette
                            value={sdColor}
                            onChange={(val) => setAttributes({sdColor: val})}
                        />
                        <SelectControl
                            label={__('Short desc Font size:', "tlp-portfolio")}
                            options={fontSizes}
                            value={sdFontSize}
                            onChange={(val) => setAttributes({sdFontSize: val})}
                        />
                        <SelectControl
                            label={__('Short desc Font weight:', "tlp-portfolio")}
                            options={fontWeights}
                            value={sdFontWeight}
                            onChange={(val) => setAttributes({sdFontWeight: val})}
                        />
                        <SelectControl
                            label={__('Short desc Alignment:', "tlp-portfolio")}
                            options={alignments}
                            value={sdAlignment}
                            onChange={(val) => setAttributes({sdAlignment: val})}
                        />
                        <TextControl
                            label={__('Wrapper Class', 'tlp-portfolio')}
                            value={wrapperClass}
                            onChange={(val) => setAttributes({wrapperClass: val})}
                        />
                    </PanelBody>
                </InspectorControls>
                ,
                <div className={props.className}>
                    <div>
                        <span><img src={rtPortfolio.icon}/></span>
                        <span> {__('TLP Portfolio (Deprecated)', "tlp-portfolio")}</span>
                    </div>
                </div>
            ]
        );
    },
    save: function () {
        return null;
    },
});