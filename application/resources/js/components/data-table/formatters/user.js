import defaults from './default';

const formatUser = (user) => {
  let content = `<small class="text-muted">#${user.id}</small>&ensp;`;

  if (user.lastName) {
    content += `${user.lastName}, `;
  }

  content += user.firstName;

  return user.links.self
    ? `<a href="${user.links.self}">${content}</a>`
    : content;
};

const replaceExtras = name => name.replace(/^(von|van|v\.)\s/, '');

export default {
  ...defaults,

  format: formatUser,
  groupBy: user => user.id,
  orderBy: user => `${user.lastName ? replaceExtras(user.lastName) : ''}${user.firstName}`.toLowerCase(),
  defaultAggregator: 'count',

  aggregates: {
    count: {
      label: 'Anzahl',
      calculate: list => list.length,
      format: 'number',
    },
  },
};
