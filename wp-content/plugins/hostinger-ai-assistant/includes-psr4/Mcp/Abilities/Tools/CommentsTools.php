<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

use WP_Comment;
use WP_REST_Comments_Controller;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class CommentsTools extends RestEndpointTool {
    use AbilityResultTrait;

    public function register(): void {
        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'                  => 'hostinger-ai/comments-search',
                    'label'                      => __( 'Search Comments', 'hostinger-ai-assistant' ),
                    'description'                => 'Search and filter comments with pagination, including WooCommerce product reviews. Use when you need a comment ID, or to review comments awaiting moderation.',
                    'input_schema_modifications' => array(
                        'properties' => array(
                            'status' => array(
                                'description' => 'Moderation status to filter by: approve, hold (pending), spam, trash or all.',
                            ),
                            'type'   => array(
                                'description' => 'Comment type to filter by, for example comment or review. Use an empty value to return all types.',
                            ),
                        ),
                    ),
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                    'permission_callback'        => function () {
                        return current_user_can( 'moderate_comments' );
                    },
                ),
                'get'    => array(
                    'tool_name'           => 'hostinger-ai/comments-get',
                    'label'               => __( 'Get Comment', 'hostinger-ai-assistant' ),
                    'description'         => 'Get one comment by ID with all its fields. Use when you need the details of a known comment. Needs `id` from `hostinger-ai/comments-search`.',
                    'meta'                => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                    'permission_callback' => function () {
                        return current_user_can( 'moderate_comments' );
                    },
                ),
                'create' => array(
                    'tool_name'                  => 'hostinger-ai/comments-create',
                    'label'                      => __( 'Create Comment', 'hostinger-ai-assistant' ),
                    'description'                => 'Create a comment on a post from its content. Use when asked to add or reply to a comment. Needs `post` from `hostinger-ai/posts-search`, and a `parent` comment ID from `hostinger-ai/comments-search` when replying.',
                    'input_schema_modifications' => array(
                        'properties' => array(
                            'post'    => array(
                                'description' => 'ID of the post the comment belongs to.',
                            ),
                            'content' => array(
                                'type'        => 'string',
                                'description' => 'Content of the comment.',
                                'properties'  => null,
                            ),
                        ),
                        'required'   => array( 'post', 'content' ),
                    ),
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => false,
                            'idempotentHint'  => false,
                            'openWorldHint'   => false,
                        ),
                    ),
                    'permission_callback'        => function () {
                        return current_user_can( 'moderate_comments' );
                    },
                ),
                'update' => array(
                    'tool_name'                  => 'hostinger-ai/comments-update',
                    'label'                      => __( 'Update Comment', 'hostinger-ai-assistant' ),
                    'description'                => 'Update a comment; only the fields you pass change. Change the moderation state with status (approve, hold, spam, trash). Use when asked to edit or moderate a comment. Needs `id` from `hostinger-ai/comments-search`.',
                    'input_schema_modifications' => array(
                        'properties' => array(
                            'content' => array(
                                'type'        => 'string',
                                'description' => 'Content of the comment.',
                                'properties'  => null,
                            ),
                        ),
                    ),
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => false,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                    'permission_callback'        => function () {
                        return current_user_can( 'moderate_comments' );
                    },
                ),
                'delete' => array(
                    'tool_name'                  => 'hostinger-ai/comments-delete',
                    'label'                      => __( 'Delete Comment', 'hostinger-ai-assistant' ),
                    'description'                => 'Delete a comment; it is moved to the trash unless force is true, which deletes it permanently. Use when asked to remove a comment. Needs `id` from `hostinger-ai/comments-search`.',
                    'input_schema_modifications' => array(
                        'type'       => 'object',
                        'properties' => array(
                            'force' => array(
                                'type'        => 'boolean',
                                'description' => 'Skip the trash and delete the comment permanently.',
                            ),
                        ),
                    ),
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => true,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                    'permission_callback'        => function () {
                        return current_user_can( 'moderate_comments' );
                    },
                ),
            ),
            WP_REST_Comments_Controller::class,
            '/wp/v2/comments',
            'comment'
        );

        $this->register_moderation_operations();
    }

    private function register_moderation_operations(): void {
        $operations = array(
            'hostinger-ai/comments-approve'   => array(
                'label'       => __( 'Approve Comment', 'hostinger-ai-assistant' ),
                'description' => 'Approve a comment, making it publicly visible. Use when asked to publish a pending comment. Needs `id` from `hostinger-ai/comments-search`.',
                'action'      => 'approve',
            ),
            'hostinger-ai/comments-unapprove' => array(
                'label'       => __( 'Unapprove Comment', 'hostinger-ai-assistant' ),
                'description' => 'Unapprove a comment, moving it back to pending moderation. Use when asked to hide a published comment until it is reviewed. Needs `id` from `hostinger-ai/comments-search`.',
                'action'      => 'unapprove',
            ),
            'hostinger-ai/comments-spam'      => array(
                'label'       => __( 'Mark Comment As Spam', 'hostinger-ai-assistant' ),
                'description' => 'Mark a comment as spam. Use when asked to flag a comment as spam. Needs `id` from `hostinger-ai/comments-search`.',
                'action'      => 'spam',
            ),
            'hostinger-ai/comments-unspam'    => array(
                'label'       => __( 'Unmark Comment As Spam', 'hostinger-ai-assistant' ),
                'description' => 'Restore a comment that was marked as spam to its previous moderation status. Use when a comment was flagged as spam by mistake. Needs `id` from `hostinger-ai/comments-search`.',
                'action'      => 'unspam',
            ),
        );

        foreach ( $operations as $tool_name => $operation ) {
            wp_register_ability(
                $tool_name,
                array(
                    'label'               => $operation['label'],
                    'description'         => $operation['description'],
                    'category'            => 'hostinger-ai-assistant',
                    'input_schema'        => array(
                        'type'       => 'object',
                        'properties' => array(
                            'id' => array(
                                'type'        => 'integer',
                                'description' => 'Unique identifier for the comment',
                            ),
                        ),
                        'required'   => array( 'id' ),
                    ),
                    'execute_callback'    => function ( $input ) use ( $operation ) {
                        return $this->to_ability_result( $this->execute_moderation( $operation['action'], is_array( $input ) ? $input : array() ) );
                    },
                    'permission_callback' => function () {
                        return current_user_can( 'moderate_comments' );
                    },
                    'meta'                => array(
                        'show_in_rest' => true,
                        'mcp'          => array(
                            'public' => true,
                            'type'   => $this->type,
                        ),
                        'annotations'  => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => false,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                )
            );
        }
    }

    private function execute_moderation( string $action, array $input ): array {
        $comment_id = isset( $input['id'] ) ? intval( $input['id'] ) : 0;

        if ( $comment_id <= 0 ) {
            return array(
                'success'    => false,
                'error_code' => 'INVALID_PARAMS',
                'message'    => __( 'A valid comment ID is required.', 'hostinger-ai-assistant' ),
            );
        }

        $comment = get_comment( $comment_id );

        if ( ! $comment instanceof WP_Comment ) {
            return array(
                'success'    => false,
                'error_code' => 'COMMENT_NOT_FOUND',
                /* translators: %s: comment id */
                'message'    => sprintf( __( "Comment with ID '%s' not found", 'hostinger-ai-assistant' ), $comment_id ),
            );
        }

        if ( ! current_user_can( 'edit_comment', $comment_id ) ) {
            return array(
                'success'    => false,
                'error_code' => 'FORBIDDEN',
                'message'    => __( 'You are not allowed to moderate this comment.', 'hostinger-ai-assistant' ),
            );
        }

        if ( $action === 'unspam' && wp_get_comment_status( $comment_id ) !== 'spam' ) {
            return array(
                'success'    => false,
                'error_code' => 'NOT_SPAM',
                'message'    => __( 'The comment is not marked as spam, so its status was left unchanged.', 'hostinger-ai-assistant' ),
            );
        }

        $result = $this->apply_moderation_action( $action, $comment_id );

        if ( ! $result ) {
            return array(
                'success'    => false,
                'error_code' => 'NOT_UPDATED',
                'message'    => __( 'The comment moderation status was not changed.', 'hostinger-ai-assistant' ),
            );
        }

        $updated_comment = get_comment( $comment_id );

        return array(
            'success' => true,
            'action'  => $action,
            'comment' => array(
                'id'     => $comment_id,
                'status' => $updated_comment instanceof WP_Comment ? wp_get_comment_status( $comment_id ) : 'deleted',
                'post'   => $updated_comment instanceof WP_Comment ? intval( $updated_comment->comment_post_ID ) : 0,
            ),
        );
    }

    private function apply_moderation_action( string $action, int $comment_id ): bool {
        switch ( $action ) {
            case 'approve':
                return (bool) wp_set_comment_status( $comment_id, 'approve' );
            case 'unapprove':
                return (bool) wp_set_comment_status( $comment_id, 'hold' );
            case 'spam':
                return (bool) wp_spam_comment( $comment_id );
            case 'unspam':
                return (bool) wp_unspam_comment( $comment_id );
        }

        return false;
    }
}
