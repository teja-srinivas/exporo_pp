export default {
  functional: true,

  props: {
    column: {
      type: Object,
      required: true,
    },

    value: {
      type: String,
      required: true,
    },
  },

  render(createElement, context) {
    const classes = ['text-nowrap', 'text-truncate'];
    const column = context.props.column;

    // Text styling changes
    classes.push({
      'center': 'text-center',
      'right': 'text-right',
    }[column.align || (column.formatter ? column.formatter.align : undefined)]);

    if (column.small) {
      classes.push('small', 'align-middle', 'text-muted');
    }

    return createElement('td', {
      class: classes,
      attrs: {
        width: column.width,
      },
      domProps: {
        innerHTML: context.props.value,
      },
    });
  },
};
