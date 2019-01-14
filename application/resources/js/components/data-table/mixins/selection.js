import findIndex from 'lodash/findIndex';
import reduce from 'lodash/reduce';
import sumBy from 'lodash/sumBy';

export default {
  methods: {
    itemIsSelected(row) {
      return this.selection.indexOf(row[this.primary]) >= 0;
    },

    getNonGroupValueCount(group) {
      return sumBy(group.values, ({ isGroup }) => isGroup ? 0 : 1);
    },

    getSelectedValueCount(group) {
      return sumBy(group.values, (value) => (
          value.isGroup ? this.getSelectedValueCount(value) : (this.itemIsSelected(value) ? 1 : 0)
      ));
    },

    isFullySelected(group) {
      // Find the first un-selected item, thus making the whole check fail
      return findIndex(group.values, (value) => (
        value.isGroup ? this.isFullySelected(value) : !this.itemIsSelected(value)
      )) < 0;
    },

    getSelectionKeysInGroup(group) {
      return reduce(group.values, (keys, value) => {
        if (value.isGroup) {
          return [...keys, this.getSelectionKeysInGroup(group)];
        }

        keys.push(value[this.primary]);
        return keys;
      }, []);
    },
  },
};
