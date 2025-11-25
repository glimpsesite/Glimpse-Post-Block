/**
 * Glimpse Post Block – Editor Script
 *
 * Registers a dynamic Gutenberg block for displaying filtered WordPress posts.
 * Uses ServerSideRender to delegate frontend output to PHP for full compatibility
 * and performance. All attributes and inspector controls are defined here.
 *
 * @since 1.0.0
 */

const { registerBlockType } = wp.blocks;
const { InspectorControls, useBlockProps } = wp.blockEditor;
const { 
    PanelBody, 
    SelectControl, 
    TextControl, 
    ToggleControl, 
    RangeControl,
    ServerSideRender 
} = wp.components;
const { __ } = wp.i18n;
const { createElement, Fragment } = wp.element;

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

        return createElement(
            Fragment,
            null,
            createElement(
                InspectorControls,
                null,
                createElement(
                    PanelBody,
                    {
                        title: __('Posts Settings', 'glimpse-post-block'),
                        initialOpen: true
                    },
                    createElement(SelectControl, {
                        label: __('Category', 'glimpse-post-block'),
                        value: attributes.category,
                        options: blockData.categories,
                        onChange: (value) => setAttributes({ category: value })
                    }),
                    createElement(SelectControl, {
                        label: __('Tags', 'glimpse-post-block'),
                        value: attributes.tags,
                        options: blockData.tags,
                        onChange: (value) => setAttributes({ tags: value }),
                        multiple: true
                    }),
                    createElement(RangeControl, {
                        label: __('Number of Posts', 'glimpse-post-block'),
                        value: attributes.numberOfPosts,
                        onChange: (value) => setAttributes({ numberOfPosts: value }),
                        min: 1,
                        max: 20
                    }),
                    createElement(SelectControl, {
                        label: __('Specific Posts', 'glimpse-post-block'),
                        value: attributes.specificPosts,
                        options: blockData.posts,
                        onChange: (value) => setAttributes({ specificPosts: value }),
                        multiple: true
                    })
                ),
                createElement(
                    PanelBody,
                    {
                        title: __('Display Options', 'glimpse-post-block'),
                        initialOpen: false
                    },
                    createElement(ToggleControl, {
                        label: __('Show Title', 'glimpse-post-block'),
                        checked: attributes.showTitle,
                        onChange: (value) => setAttributes({ showTitle: value })
                    }),
                    createElement(ToggleControl, {
                        label: __('Show Featured Image', 'glimpse-post-block'),
                        checked: attributes.showImage,
                        onChange: (value) => setAttributes({ showImage: value })
                    }),
                    createElement(ToggleControl, {
                        label: __('Show Excerpt', 'glimpse-post-block'),
                        checked: attributes.showExcerpt,
                        onChange: (value) => setAttributes({ showExcerpt: value })
                    }),
                    createElement(ToggleControl, {
                        label: __('Link on Title', 'glimpse-post-block'),
                        checked: attributes.titleLink,
                        onChange: (value) => setAttributes({ titleLink: value })
                    }),
                    createElement(ToggleControl, {
                        label: __('Show Read More Button', 'glimpse-post-block'),
                        checked: attributes.buttonLink,
                        onChange: (value) => setAttributes({ buttonLink: value })
                    }),
                    attributes.buttonLink ? createElement(TextControl, {
                        label: __('Button Text', 'glimpse-post-block'),
                        value: attributes.buttonText,
                        onChange: (value) => setAttributes({ buttonText: value })
                    }) : null,
                    createElement(RangeControl, {
                        label: __('Excerpt Length', 'glimpse-post-block'),
                        value: attributes.excerptLength,
                        onChange: (value) => setAttributes({ excerptLength: value }),
                        min: 10,
                        max: 100
                    })
                )
            ),
            createElement(
                'div',
                blockProps,
                createElement(ServerSideRender, {
                    block: 'glimpse-post-block/posts',
                    attributes: attributes
                })
            )
        );
    };

    // Register the block.
    registerBlockType('glimpse-post-block/posts', {
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
            excerptLength: {
                type: 'number',
                default: 20,
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