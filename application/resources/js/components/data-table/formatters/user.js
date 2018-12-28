import defaults from './default';

const formatUser = (user) => `<a href="${user.links.self}">
  <small class="text-muted">#${user.id}</small>
  ${user.lastName}, ${user.firstName}
</a>`;

const replaceExtras = name => name.replace(/^(von|van)\s/, '');

export default {
  ...defaults,

  format: formatUser,
  groupBy: user => user.id,
  orderBy: user => `${replaceExtras(user.lastName)}${user.firstName}`.toLowerCase(),
  defaultAggregator: 'count',

  aggregates: {
    count: {
      label: 'Anzahl',
      calculate: list => list.length,
      format: 'number',
    },
  },
};
