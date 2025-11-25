/**
 * Glimpse Post Block – Editor Script
 *
 * Registers a dynamic Gutenberg block for displaying filtered WordPress posts.
 * Uses ServerSideRender to delegate frontend output to PHP for full compatibility
 * and performance. All attributes and inspector controls are defined here.
 *
 * @since 1.0.0
 */

(function (blocks, element, blockEditor, components, i18n) {
    'use strict';

    const { __ } = i18n;
    const { createElement: el, Fragment } = element;
    const { InspectorControls, useBlockProps } = blockEditor;
    const {
        PanelBody,
        SelectControl,
        TextControl,
        ToggleControl,
        RangeControl,
        ServerSideRender,
    } = components;

    /**
     * Edit component for the Glimpse Post Block.
     *
     * Renders block controls in the sidebar and a live preview via ServerSideRender.
     *
     * @param {Object}   props               Block props.
     * @param {Object}   props.attributes    Block attributes.
     * @param {Function} props.setAttributes Function to update block attributes.
     * @return {WPElement} React element for the block editor UI.
     */
    const GlimpsePostsBlockEdit = (props) => {
        const { attributes, setAttributes } = props;
        const blockProps = useBlockProps();

        // Fallback in case localized data is missing (e.g., during unit tests).
        const blockData = window.glimpsePostBlock || {
            categories: [],
            tags: [],
            posts: [],
        };

        return (
            <Fragment>
                <InspectorControls>
                    <PanelBody
                        title={__('Posts Settings', 'glimpse-post-block')}
                        initialOpen={true}
                    >
                        <SelectControl
                            label={__('Category', 'glimpse-post-block')}
                            value={attributes.category}
                            options={blockData.categories}
                            onChange={(value) =>
                                setAttributes({ category: value })
                            }
                        />
                        <SelectControl
                            label={__('Tags', 'glimpse-post-block')}
                            value={attributes.tags}
                            options={blockData.tags}
                            onChange={(value) =>
                                setAttributes({ tags: value })
                            }
                            multiple
                        />
                        <RangeControl
                            label={__('Number of Posts', 'glimpse-post-block')}
                            value={attributes.numberOfPosts}
                            onChange={(value) =>
                                setAttributes({ numberOfPosts: value })
                            }
                            min={1}
                            max={20}
                        />
                        <SelectControl
                            label={__('Specific Posts', 'glimpse-post-block')}
                            value={attributes.specificPosts}
                            options={blockData.posts}
                            onChange={(value) =>
                                setAttributes({ specificPosts: value })
                            }
                            multiple
                        />
                    </PanelBody>

                    <PanelBody
                        title={__('Display Options', 'glimpse-post-block')}
                        initialOpen={false}
                    >
                        <ToggleControl
                            label={__('Show Title', 'glimpse-post-block')}
                            checked={attributes.showTitle}
                            onChange={(value) =>
                                setAttributes({ showTitle: value })
                            }
                        />
                        <ToggleControl
                            label={__('Show Featured Image', 'glimpse-post-block')}
                            checked={attributes.showImage}
                            onChange={(value) =>
                                setAttributes({ showImage: value })
                            }
                        />
                        <ToggleControl
                            label={__('Show Excerpt', 'glimpse-post-block')}
                            checked={attributes.showExcerpt}
                            onChange={(value) =>
                                setAttributes({ showExcerpt: value })
                            }
                        />
                        <ToggleControl
                            label={__('Link on Title', 'glimpse-post-block')}
                            checked={attributes.titleLink}
                            onChange={(value) =>
                                setAttributes({ titleLink: value })
                            }
                        />
                        <ToggleControl
                            label={__('Show Read More Button', 'glimpse-post-block')}
                            checked={attributes.buttonLink}
                            onChange={(value) =>
                                setAttributes({ buttonLink: value })
                            }
                        />
                        {attributes.buttonLink && (
                            <TextControl
                                label={__('Button Text', 'glimpse-post-block')}
                                value={attributes.buttonText}
                                onChange={(value) =>
                                    setAttributes({ buttonText: value })
                                }
                            />
                        )}
                    </PanelBody>
                </InspectorControls>

                <div {...blockProps}>
                    <ServerSideRender
                        block="glimpse-post-block/posts"
                        attributes={attributes}
                    />
                </div>
            </Fragment>
        );
    };

    // Register the block.
    blocks.registerBlockType('glimpse-post-block/posts', {
        title: __('Glimpse Posts', 'glimpse-post-block'),
        description: __(
            'Display posts with advanced filtering options.',
            'glimpse-post-block'
        ),
        icon: 'admin-post',
        category: 'widgets',
        supports: {
            html: false, // Disable editing as HTML.
            className: true,
        },
        attributes: {
            category: {
                type: 'string',
                default: '',
            },
            tags: {
                type: 'array',
                default: [],
                items: {
                    type: 'string',
                },
            },
            numberOfPosts: {
                type: 'number',
                default: 5,
            },
            specificPosts: {
                type: 'array',
                default: [],
                items: {
                    type: 'string',
                },
            },
            showTitle: {
                type: 'boolean',
                default: true,
            },
            showImage: {
                type: 'boolean',
                default: true,
            },
            showExcerpt: {
                type: 'boolean',
                default: true,
            },
            titleLink: {
                type: 'boolean',
                default: true,
            },
            buttonLink: {
                type: 'boolean',
                default: false,
            },
            buttonText: {
                type: 'string',
                default: __('Read More', 'glimpse-post-block'),
            },
            className: {
                type: 'string',
                default: '',
            },
        },
        edit: GlimpsePostsBlockEdit,
        save: () => null, // Dynamic block; rendered via PHP.
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor,
    window.wp.components,
    window.wp.i18n
);