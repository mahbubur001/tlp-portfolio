//  Import CSS.
import './scss/style.scss';
import './scss/editor.scss';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;
const {SelectControl, PanelBody, PanelRow, TextControl, CheckboxControl} = wp.components;
const {InspectorControls, ColorPalette} = wp.editor;

registerBlockType('radiustheme/tlp-portfolio', {
    title: __('Tlp Portfolio', "tlp-portfolio"),
    icon: 'grid-view',
    description: __('Settings section', "tlp-portfolio"),
    category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
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
        if (tlpPortfolio.layout) {
            for (const [id, title] of Object.entries(tlpPortfolio.layout)) {
                layouts.push({
                    value: id,
                    label: title
                });
            }
        }
        if (tlpPortfolio.column) {
            for (const [id, title] of Object.entries(tlpPortfolio.column)) {
                columns.push({
                    value: id,
                    label: title
                });
            }
        }
        if (tlpPortfolio.orderby) {
            for (const [id, title] of Object.entries(tlpPortfolio.orderby)) {
                orderByS.push({
                    value: id,
                    label: title
                });
            }
        }
        if (tlpPortfolio.order) {
            for (const [id, title] of Object.entries(tlpPortfolio.order)) {
                orders.push({
                    value: id,
                    label: title
                });
            }
        }
        if (tlpPortfolio.cats) {
            for (const [id, title] of Object.entries(tlpPortfolio.cats)) {
                catList.push({
                    value: id,
                    label: title
                });
            }
        }
        if (tlpPortfolio.alignments) {
            for (const [id, title] of Object.entries(tlpPortfolio.alignments)) {
                alignments.push({
                    value: id,
                    label: title
                });
            }
        }
        if (tlpPortfolio.fontWeights) {
            for (const [id, title] of Object.entries(tlpPortfolio.fontWeights)) {
                fontWeights.push({
                    value: id,
                    label: title
                });
            }
        }
        if (tlpPortfolio.fontSizes) {
            for (const [id, title] of Object.entries(tlpPortfolio.fontSizes)) {
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
                        <span><img src={tlpPortfolio.icon}/></span>
                        <span> {__('Tlp Portfolio', "tlp-portfolio")}</span>
                    </div>
                </div>
            ]
        );
    },
    save: function () {
        return null;
    },
});